<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        if (!$settings->get('profile_body')) {
            $settings['profile_body'] = \App\Http\Controllers\AboutController::defaultBody();
        }
        if (!$settings->get('studio_body')) {
            $settings['studio_body'] = \App\Http\Controllers\AboutController::defaultStudioBody();
        }
        return view('admin.about.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'profile_image'      => 'nullable|string|max:500',
            'profile_heading'    => 'nullable|string|max:255',
            'profile_heading_en' => 'nullable|string|max:255',
            'profile_body'       => 'nullable|string',
            'profile_body_en'    => 'nullable|string',
            'studio_image'       => 'nullable|string|max:500',
            'studio_body'        => 'nullable|string',
            'studio_body_en'     => 'nullable|string',
        ]);

        foreach ([
            'profile_image', 'profile_heading', 'profile_heading_en', 'profile_body', 'profile_body_en',
            'studio_image', 'studio_body', 'studio_body_en',
        ] as $key) {
            if ($request->exists($key)) {
                Setting::set($key, $request->input($key, ''));
            }
        }

        return redirect()->route('admin.about.index')->with('success', 'Profil sayfası güncellendi.');
    }
}
