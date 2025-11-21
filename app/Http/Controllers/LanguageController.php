<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Available languages
     */
    public static $languages = [
        'en' => ['name' => 'English', 'flag' => '🇬🇧', 'dir' => 'ltr'],
        'ar' => ['name' => 'العربية', 'flag' => '🇸🇦', 'dir' => 'rtl'],
        'es' => ['name' => 'Español', 'flag' => '🇪🇸', 'dir' => 'ltr'],
        'fr' => ['name' => 'Français', 'flag' => '🇫🇷', 'dir' => 'ltr'],
        'de' => ['name' => 'Deutsch', 'flag' => '🇩🇪', 'dir' => 'ltr'],
        'it' => ['name' => 'Italiano', 'flag' => '🇮🇹', 'dir' => 'ltr'],
        'pt' => ['name' => 'Português', 'flag' => '🇵🇹', 'dir' => 'ltr'],
        'ru' => ['name' => 'Русский', 'flag' => '🇷🇺', 'dir' => 'ltr'],
        'zh' => ['name' => '中文', 'flag' => '🇨🇳', 'dir' => 'ltr'],
        'ja' => ['name' => '日本語', 'flag' => '🇯🇵', 'dir' => 'ltr'],
        'ko' => ['name' => '한국어', 'flag' => '🇰🇷', 'dir' => 'ltr'],
        'hi' => ['name' => 'हिन्दी', 'flag' => '🇮🇳', 'dir' => 'ltr'],
        'tr' => ['name' => 'Türkçe', 'flag' => '🇹🇷', 'dir' => 'ltr'],
    ];

    /**
     * Switch language
     */
    public function switch(Request $request, $locale)
    {
        // Validate locale
        if (!array_key_exists($locale, self::$languages)) {
            abort(404);
        }

        // Set locale
        App::setLocale($locale);
        Session::put('locale', $locale);

        // Redirect back
        return redirect()->back();
    }

    /**
     * Get current language
     */
    public static function current()
    {
        $locale = Session::get('locale', config('app.locale'));
        return self::$languages[$locale] ?? self::$languages['en'];
    }

    /**
     * Get all languages
     */
    public static function all()
    {
        return self::$languages;
    }
}
