<?php

namespace app\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FinAssistService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = 'https://api.openai.com/v1/chat/completions';
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function getResponse(string $userQuery, array $context = [])
    {
        try {
            $payload = [
                'model' => 'gpt-3.5-turbo',
                'messages' => array_merge([
                    ['role' => 'system', 'content' => 'You are FinAssist, an expert AI advisor for Roman Stock Manager. Provide financial and inventory insights based on user queries.'],
                    ['role' => 'user', 'content' => $userQuery]
                ], $context),
                'temperature' => 0.7,
                'max_tokens' => 300,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, $payload);

            if ($response->successful()) {
                return $response->json()['choices'][0]['message']['content'] ?? 'No response from AI';
            }

            Log::error('FinAssist API Error', ['response' => $response->body()]);
            return 'Sorry, there was an issue processing your request.';
        } catch (\Exception $e) {
            Log::error('FinAssistService Exception', ['error' => $e->getMessage()]);
            return 'An error occurred while fetching the response.';
        }
    }
}
