<?php

namespace App\Services;

use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class AiReportService
{
    public function generate(
        string $reportType,
        CarbonInterface $start,
        CarbonInterface $end,
        array $metrics
    ): array {
        $apiKey = trim((string) config('ai-reports.gemini.api_key'));

        if ($apiKey === '') {
            return [
                'summary' => null,
                'generated' => false,
                'error' => 'Gemini is not configured. Add GEMINI_API_KEY to the .env file.',
            ];
        }

        $model = (string) config(
            'ai-reports.gemini.model',
            'gemini-2.5-flash'
        );
        $baseUrl = rtrim(
            (string) config('ai-reports.gemini.base_url'),
            '/'
        );
        $timeout = max(
            (int) config('ai-reports.gemini.timeout', 30),
            5
        );

        try {
            $response = Http::acceptJson()
                ->withHeaders([
                    'x-goog-api-key' => $apiKey,
                ])
                ->timeout($timeout)
                ->post(
                    $baseUrl.'/models/'.rawurlencode($model).':generateContent',
                    [
                        'contents' => [[
                            'role' => 'user',
                            'parts' => [[
                                'text' => $this->prompt(
                                    $reportType,
                                    $start,
                                    $end,
                                    $metrics
                                ),
                            ]],
                        ]],
                        'generationConfig' => [
                            'temperature' => 0.2,
                            'maxOutputTokens' => 1000,
                        ],
                    ]
                );

            if ($response->failed()) {
                Log::warning('Gemini report generation failed.', [
                    'status' => $response->status(),
                    'report_type' => $reportType,
                ]);

                return [
                    'summary' => null,
                    'generated' => false,
                    'error' => 'The AI service could not generate the narrative. The database metrics were saved successfully.',
                ];
            }

            $parts = $response->json('candidates.0.content.parts', []);
            $summary = trim(collect($parts)
                ->pluck('text')
                ->filter()
                ->implode("\n"));

            if ($summary === '') {
                return [
                    'summary' => null,
                    'generated' => false,
                    'error' => 'The AI service returned an empty narrative. The database metrics were saved successfully.',
                ];
            }

            return [
                'summary' => $summary,
                'generated' => true,
                'error' => null,
            ];
        } catch (Throwable $exception) {
            report($exception);

            return [
                'summary' => null,
                'generated' => false,
                'error' => 'The AI service is temporarily unavailable. The database metrics were saved successfully.',
            ];
        }
    }

    private function prompt(
        string $reportType,
        CarbonInterface $start,
        CarbonInterface $end,
        array $metrics
    ): string {
        $json = json_encode(
            $metrics,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );

        return <<<PROMPT
You are an operations analyst for a Blood Sample Circulation Management System.

Create a concise administrative report for the period {$start->toDateString()} to {$end->toDateString()}.
Report type: {$reportType}.

Use only the aggregate database metrics below. Do not invent causes, patients, medical conclusions, dates, or events that are not present. If the data is insufficient, state that clearly. Do not expose or request personal data.

Write plain text under exactly these headings:
EXECUTIVE SUMMARY
KEY FINDINGS
OPERATIONAL RISKS
RECOMMENDED ACTIONS

Keep the response under 500 words. Use short numbered items under the last three headings. Explain zero values accurately rather than treating them as system failures.

AGGREGATE DATABASE METRICS:
{$json}
PROMPT;
    }
}
