<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class JudgeService
{
    /**
     * Create a new class instance.
     */
    protected string $baseUrl;
    public function __construct()
    {
        $this->baseUrl = rtrim(
            config('services.judge.url'),
            '/'
        );

    }
    public function submit(array $data): array
    {
        $response = Http::acceptJson()
            ->timeout(15)
            ->post(
                $this->baseUrl . '/api/v1/submissions/',
                $data
            );

        $response->throw();

        return $response->json();
    }
    public function getSubmission(string $jobId): array
    {
        $response = Http::acceptJson()
            ->timeout(10)
            ->get(
                $this->baseUrl . '/api/v1/submissions/' . rawurlencode($jobId)
            );

        $response->throw();

        return $response->json();
    }
    public function getLanguages():array
    {
        $response = Http::acceptJson()->timeout(15)->get($this->baseUrl.'/api/v1/languages');
        $response->throw();
        return $response->json();
    }
}
