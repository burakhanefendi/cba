<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'logo'            => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'default_locale'  => 'required|in:tr,en',
            'contact_map_url' => 'nullable|url',
        ]);

        if ($request->hasFile('logo')) {
            $old = Setting::get('logo');
            if ($old) Storage::disk('public')->delete($old);

            $path = $request->file('logo')->store('settings', 'public');
            Setting::set('logo', $path);
        }

        $fields = ['site_title', 'site_description', 'contact_email', 'contact_phone', 'contact_address', 'contact_map_url', 'default_locale'];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->input($field));
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Ayarlar kaydedildi.');
    }
}
