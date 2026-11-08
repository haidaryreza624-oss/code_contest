<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Models\ProblemTestCase;
use App\Models\Submission;
use App\Services\JudgeService;
use Illuminate\Http\Request;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use App\Jobs\ProcessSubmissionResult;

class SubmissionController extends Controller
{
    public function store(Request $request,string $slug,JudgeService $judge)
    {
        $problem = Problem::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Validate uploaded file.
        $validated = $request->validate([
            'source_file' => 'required|file|max:100',
        ]);

        $file = $validated['source_file'];

        // Detect language.
        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        $languages = [
            'cpp' => 'cpp',
            'cc' => 'cpp',
            'cxx' => 'cpp',
            'py' => 'python',
        ];

        if (!isset($languages[$extension])) {
            return back()->withErrors([
                'source_file' =>
                    'Unsupported file type. Currently supported: C++ and Python.',
            ]);
        }

        $language = $languages[$extension];

        // Read source code.
        $sourceCode = $file->get();

        if ($sourceCode === false || trim($sourceCode) === '') {
            return back()->withErrors([
                'source_file' => 'The uploaded file is empty or unreadable.',
            ]);
        }

        // Retrieve test cases from Laravel database.
        // Both sample and hidden cases are sent to the judge.
        $testCases = ProblemTestCase::where(
                'problem_id',
                $problem->id
            )
            ->orderBy('position')
            ->get();

        if ($testCases->isEmpty()) {
            return back()->withErrors([
                'source_file' => 'This problem does not have any test cases.',
            ]);
        }

        // Convert test cases into Judge Engine format.
        $judgeTestCases = $testCases->map(function ($testCase) {

            return [
                'stdin' => $testCase->input,
                'expected_output' => $testCase->expected_output,
            ];

        })->values()->all();

        // Create submission record first.
        $submission = Submission::create([
            'user_id' => auth()->id(),
            'problem_id' => $problem->id,
            'language' => $language,
            'source_code' => $sourceCode,
            'execution_status' => 'pending',
            'total_test_cases' => count($judgeTestCases),
        ]);

        try {

            // Send submission to FastAPI.
            $job = $judge->submit([

                'language' => $language,

                'source_code' => $sourceCode,

                'test_cases' => $judgeTestCases,

                'time_limit' => $problem->time_limit_ms / 1000,

                'memory_limit' => $problem->memory_limit_mb * 1024,

            ]);

            // Store the Judge Engine job ID.
            if (empty($job['job_id'])) {
                throw new \RuntimeException(
                    'Judge Engine did not return a job ID.'
                );
            }

            $submission->update([

                'judge_job_id' => $job['job_id'],

                'execution_status' => 'queued',

            ]);
            ProcessSubmissionResult::dispatch($submission->id)
            ->delay(now()->addSeconds(3));

            return redirect()
                ->route('problems.show', $problem->slug)
                ->with(
                    'success',
                    'Your solution has been submitted and added to the judging queue.'
                );

        } catch (ConnectionException | RequestException | \RuntimeException $e) {

            $submission->update([

                'execution_status' => 'failed',

                'verdict' => 'Internal Error',

                'error_message' => $e->getMessage(),

            ]);

            return redirect()
                ->route('problems.show', $problem->slug)
                ->with(
                    'error',
                    'The Judge Engine could not process your submission. Please try again later.'
                );
        }
    }
}
