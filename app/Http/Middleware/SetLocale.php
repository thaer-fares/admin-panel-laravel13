<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale');

        if (!$locale && $request->user()) {
            $locale = $request->user()->locale;
        }

        $locale = in_array($locale, ['ar', 'en']) ? $locale : 'ar';

        App::setLocale($locale);
        session(['locale' => $locale]);

        return $next($request);
    }
}
