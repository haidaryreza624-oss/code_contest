<?php

namespace App\Jobs;

use App\Models\Submission;
use App\Services\JudgeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;
use RuntimeException;

class ProcessSubmissionResult implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [5, 10, 15];

    protected int $submissionId;

    public function __construct(int $submissionId)
    {
        $this->submissionId = $submissionId;
    }

    public function handle(JudgeService $judge): void
    {
        $submission = Submission::find($this->submissionId);

        if (!$submission || !$submission->judge_job_id) {
            return;
        }

        // Ask FastAPI for the current job status.
        $job = $judge->getSubmission(
            $submission->judge_job_id
        );

        $jobStatus = $job['job_status'] ?? null;

        /*
         * The Judge Engine is still processing.
         * Schedule another check after 3 seconds.
         */
        if (in_array($jobStatus, ['Queued', 'Running'], true)) {

            $submission->update([
                'execution_status' => strtolower($jobStatus),
            ]);

            self::dispatch($this->submissionId)
                ->delay(now()->addSeconds(3));

            return;
        }
        if ($jobStatus === 'Failed') {

            $submission->update([
                'execution_status' => 'failed',
                'verdict' => 'Internal Error',
                'error_message' => 'The Judge Engine failed to execute this submission.',
            ]);
            return;
        }

        /*
         * Execution completed.
         */
        if ($jobStatus === 'Completed') {

            $result = $job['result'] ?? null;

            if (!is_array($result)) {
                throw new RuntimeException(
                    'The Judge Engine returned an invalid completed result.'
                );
            }

            $testResults = $result['test_results'] ?? [];

            // Find the highest reported memory usage.
            $memoryUsage = null;

            foreach ($testResults as $testResult) {

                $memory = $testResult['memory_usage'] ?? null;

                if (is_numeric($memory)) {
                    $memoryUsage = max(
                        $memoryUsage ?? 0,
                        (int) $memory
                    );
                }
            }

            // Convert execution time from seconds to milliseconds.
            $executionTime = isset($result['execution_time'])
                ? (int) round($result['execution_time'] * 1000)
                : null;

            $submission->update([

                'execution_status' => 'completed',

                'verdict' => $result['status'] ?? 'Internal Error',

                'passed_test_cases' =>
                    $result['passed_test_cases'] ?? 0,

                'total_test_cases' =>
                    $result['total_test_cases'] ?? $submission->total_test_cases,

                'execution_time_ms' => $executionTime,

                'memory_used_kb' => $memoryUsage,

            ]);

            return;
        }

        throw new RuntimeException(
            'Unknown Judge Engine job status: ' . ($jobStatus ?? 'null')
        );
    }

    public function failed(?Throwable $exception): void
    {
        Submission::where('id', $this->submissionId)
            ->whereNotIn('execution_status', ['completed', 'failed'])
            ->update([

                'execution_status' => 'failed',

                'verdict' => 'Internal Error',

                'error_message' =>
                    'Unable to retrieve the Judge Engine result after multiple attempts.',

            ]);
    }
}
