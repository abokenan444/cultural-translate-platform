<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Supported languages
     */
    protected $supportedLanguages = [
        'en', // English
        'ar', // Arabic
        'es', // Spanish
        'fr', // French
        'de', // German
        'it', // Italian
        'pt', // Portuguese
        'ru', // Russian
        'zh', // Chinese
        'ja', // Japanese
        'ko', // Korean
        'hi', // Hindi
        'tr', // Turkish
        'nl', // Dutch
    ];

    /**
     * RTL languages
     */
    protected $rtlLanguages = ['ar', 'he', 'fa', 'ur'];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Priority: Query Parameter > Session > Cookie > Browser > Default
        $locale = $request->query('lang')
                  ?? Session::get('locale') 
                  ?? $request->cookie('locale')
                  ?? $this->getBrowserLocale($request)
                  ?? config('app.locale');
        
        // Convert to lowercase for consistency
        $locale = strtolower($locale);
        
        // Validate and set locale
        if (in_array($locale, $this->supportedLanguages)) {
            App::setLocale($locale);
            
            // Store in session
            Session::put('locale', $locale);
            
            // Set direction for RTL languages
            if (in_array($locale, $this->rtlLanguages)) {
                config(['app.direction' => 'rtl']);
            } else {
                config(['app.direction' => 'ltr']);
            }
        }
        
        return $next($request);
    }
    
    /**
     * Get browser preferred language
     */
    private function getBrowserLocale(Request $request)
    {
        $browserLang = $request->server('HTTP_ACCEPT_LANGUAGE');
        
        if (!$browserLang) {
            return null;
        }
        
        // Parse Accept-Language header
        $languages = explode(',', $browserLang);
        
        foreach ($languages as $lang) {
            $lang = strtolower(substr($lang, 0, 2));
            
            if (in_array($lang, $this->supportedLanguages)) {
                return $lang;
            }
        }
        
        return null;
    }
}
