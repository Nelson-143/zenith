<?php
namespace app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use app\Services\TranslationService;
use Illuminate\Support\Facades\Log;

class LocaleController extends Controller
{
   protected $translationService;

public function __construct(TranslationService $translationService)
{
    $this->translationService = $translationService;
}

/**
 * Change the application's locale.
 *
 * @param string $locale The locale to switch to (e.g., 'en', 'sw').
 * @return \Illuminate\Http\RedirectResponse
 */

public function change($locale)
{
    // Validate the locale
    if (!in_array($locale, ['en', 'sw'])) {
        return redirect()->back()->with('error', 'Invalid language selected.');
    }

    // Store the locale in the session
    Session::put('locale', $locale);

    // Debugging: Log the locale
    \Log::info('Locale changed to:', ['locale' => $locale]);

    // Redirect back to the previous page
    return redirect()->back();
}
/**
 * Translate dynamic content using MyMemory API.
 *
 * @param string $locale The target locale.
 */
protected function translateDynamicContent($locale)
{
    // Example: Translate dynamic content stored in the session or database
    $sourceLanguage = $locale === 'sw' ? 'en' : 'sw'; // Swap source and target languages
    $dynamicText = Session::get('dynamic_text', 'Default dynamic text');

    \Log::info('Translating dynamic content from ' . $sourceLanguage . ' to ' . $locale);
    \Log::info('Original text: ' . $dynamicText);

    $translatedText = $this->translationService->translate($dynamicText, $sourceLanguage, $locale);

    \Log::info('Translated text: ' . $translatedText);

    // Store the translated text in the session
    Session::put('dynamic_text', $translatedText);
}
    }
