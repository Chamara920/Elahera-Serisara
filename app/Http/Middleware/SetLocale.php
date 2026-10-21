<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SetLocale
 *
 * Sets the active application locale from session. Default is English ('en').
 */
class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', 'en');
        if (!in_array($locale, ['en', 'si'])) {
            $locale = 'en';
        }
        
        App::setLocale($locale);

        return $next($request);
    }
}
