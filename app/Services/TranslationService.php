<?php
namespace app\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TranslationService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.mymemory.translated.net/',
        ]);
    }

    /**
     * Translate text from one language to another using MyMemory API.
     *
     * @param string $text The text to translate.
     * @param string $sourceLanguage The source language code (e.g., 'en' for English).
     * @param string $targetLanguage The target language code (e.g., 'sw' for Swahili).
     * @return string The translated text.
     */
    public function translate(string $text, string $sourceLanguage, string $targetLanguage)
{
    $cacheKey = md5("translation_{$text}_{$sourceLanguage}_{$targetLanguage}");

    return Cache::remember($cacheKey, now()->addDay(), function () use ($text, $sourceLanguage, $targetLanguage) {
        $response = $this->client->get('get', [
            'query' => [
                'q' => $text,
                'langpair' => "{$sourceLanguage}|{$targetLanguage}",
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        // Debugging: Log the API response
        \Log::info('MyMemory API Response:', $data);

        return $data['responseData']['translatedText'] ?? $text;
    });
}
}