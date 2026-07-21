<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $firstSegment = $request->segment(1);
        $locale = ($firstSegment === 'en') ? 'en' : 'tr';

        app()->setLocale($locale);
        view()->share('locale', $locale);

        return $next($request);
    }
}
