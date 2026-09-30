<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $firstSegment = $request->segment(1);
        $locale = ($firstSegment === 'en') ? 'en' : 'tr';

        if (
            $request->routeIs('home')
            && in_array($request->method(), ['GET', 'HEAD'], true)
        ) {
            $preferred = $request->cookie('site_locale') ?: Setting::defaultLocale();

            if ($preferred === 'en') {
                return redirect('/en');
            }
        }

        app()->setLocale($locale);
        view()->share('locale', $locale);

        $response = $next($request);

        return $response->cookie('site_locale', $locale, 60 * 24 * 365);
    }
}
