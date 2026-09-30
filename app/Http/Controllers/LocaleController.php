<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (! in_array($locale, ['tr', 'en'], true)) {
            abort(404);
        }

        $to = $this->safeTarget($request, $request->query('to'), $locale);

        return redirect($to)->cookie('site_locale', $locale, 60 * 24 * 365);
    }

    private function safeTarget(Request $request, ?string $to, string $locale): string
    {
        $fallback = $locale === 'en' ? url('/en') : url('/');

        if (! $to) {
            return $fallback;
        }

        if (str_starts_with($to, '/') && ! str_starts_with($to, '//')) {
            return url($to);
        }

        $host = parse_url($to, PHP_URL_HOST);

        if ($host && strcasecmp($host, $request->getHost()) === 0) {
            return $to;
        }

        return $fallback;
    }
}
