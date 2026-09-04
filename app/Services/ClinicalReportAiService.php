<?php

namespace App\Services;

use App\Models\SampleReport;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class ClinicalReportAiService
{
    public function generate(SampleReport $report): array
    {
        $apiKey = (string) config('ai-reports.gemini.api_key');
        $baseUrl = rtrim((string) config('ai-reports.gemini.base_url'), '/');
        $model = (string) config('ai-reports.gemini.model');

        if ($apiKey === '') {
            return ['success' => false, 'error' => 'Gemini API key is not configured.'];
        }

        $report->loadMissing('bloodSample', 'results');

        $payload = [
            'sample_type' => $report->bloodSample?->sample_type,
            'results' => $report->results->map(fn ($result) => [
                'test' => $result->test_name,
                'value' => $result->result_value,
                'unit' => $result->unit,
                'reference_range' => $result->reference_range,
                'flag' => $result->flag,
                'note' => $result->notes,
            ])->values()->all(),
        ];

        $prompt = <<<'PROMPT'
You are assisting a licensed doctor who is preparing a laboratory report explanation for a patient.
Use only the structured data below. Do not diagnose a disease, prescribe treatment, invent facts, or claim certainty.
Explain the results in calm, simple language. Clearly identify any value marked low, high, or critical and advise the patient to discuss it with their doctor. If all values are marked normal, say that the listed values are within the supplied reference information, while noting that only a doctor can interpret them in clinical context.

Use these headings:
OVERVIEW
RESULTS EXPLANATION
QUESTIONS FOR YOUR DOCTOR
IMPORTANT NOTE

Keep the response below 450 words. The doctor will review and approve it before the patient sees it.
Return plain text only. Do not use Markdown symbols such as #, ##, ###, **, or bullet asterisks.

STRUCTURED REPORT DATA:
PROMPT;

        try {
            $response = Http::timeout((int) config('ai-reports.gemini.timeout', 60))
                ->withHeaders(['x-goog-api-key' => $apiKey])
                ->post("{$baseUrl}/models/{$model}:generateContent", [
                    'contents' => [[
                        'parts' => [[
                            'text' => $prompt."\n".json_encode($payload, JSON_PRETTY_PRINT),
                        ]],
                    ]],
                    'generationConfig' => [
                        'temperature' => 0.2,
                        'maxOutputTokens' => 1200,
                    ],
                ]);

            if (! $response->successful()) {
                throw new RuntimeException('Gemini returned HTTP '.$response->status().'.');
            }

            $text = trim((string) data_get($response->json(), 'candidates.0.content.parts.0.text'));

            if ($text === '') {
                throw new RuntimeException('Gemini returned an empty explanation.');
            }

            return ['success' => true, 'text' => $text];
        } catch (Throwable $exception) {
            report($exception);

            return [
                'success' => false,
                'error' => 'The AI explanation could not be generated. The saved laboratory values were not changed.',
            ];
        }
    }
}
