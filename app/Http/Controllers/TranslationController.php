<?php

namespace app\Http\Controllers;

use app\Services\TranslationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class TranslationController extends Controller
{
  protected $translationService;

public function __construct(TranslationService $translationService)
{
    $this->translationService = $translationService;
}

/**
 * Translate text.
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function translate(Request $request)
{
    $request->validate([
        'text' => 'required|string',
        'source_language' => 'required|string|in:en,sw', // Only allow English and Swahili
        'target_language' => 'required|string|in:en,sw',
    ]);

    $translatedText = $this->translationService->translate(
        $request->input('text'),
        $request->input('source_language'),
        $request->input('target_language')
    );

    \Log::info('Translation Request', [
        'text' => $request->input('text'),
        'source_language' => $request->input('source_language'),
        'target_language' => $request->input('target_language'),
        'translated_text' => $translatedText,
    ]);

    return response()->json([
        'translated_text' => $translatedText,
    ]);
}
    
}