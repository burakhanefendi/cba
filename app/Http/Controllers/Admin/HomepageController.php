<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::orderBy('order')->get();
        $settings = Setting::pluck('value', 'key');
        return view('admin.homepage.index', compact('slides', 'settings'));
    }

    public function update(Request $request)
    {
        // Intro metin — sadece bu form gönderildiyse kaydet
        if ($request->has('intro_text')) {
            Setting::set('intro_text', $request->input('intro_text', ''));
        }
        if ($request->has('intro_text_en')) {
            Setting::set('intro_text_en', $request->input('intro_text_en', ''));
        }

        // Slide sırası / link güncelle
        foreach ($request->input('slide_ids', []) as $i => $id) {
            HeroSlide::where('id', $id)->update([
                'link'  => $request->input("slide_links.{$id}"),
                'order' => $i,
            ]);
        }

        return redirect()->route('admin.homepage.index')->with('success', 'Anasayfa güncellendi.');
    }

    public function storeSlide(Request $request)
    {
        $request->validate(['image_path' => 'required|string']);

        $maxOrder = HeroSlide::max('order') ?? -1;

        HeroSlide::create([
            'image' => $request->input('image_path'),
            'link'  => $request->input('link'),
            'order' => $maxOrder + 1,
        ]);

        return redirect()->route('admin.homepage.index')->with('success', 'Slide eklendi.');
    }

    public function destroySlide(HeroSlide $slide)
    {
        $slide->delete();
        return redirect()->route('admin.homepage.index')->with('success', 'Slide silindi.');
    }
}
