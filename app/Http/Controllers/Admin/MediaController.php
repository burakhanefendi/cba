<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $media = Media::latest()->paginate(40);
        return view('admin.media.index', compact('media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:jpg,jpeg,png,gif,webp,svg|max:8192',
        ]);

        $uploaded = [];

        foreach ($request->file('files', []) as $file) {
            $path     = $file->store('media', 'public');
            $fullPath = storage_path('app/public/' . $path);

            [$width, $height] = @getimagesize($fullPath) ?: [null, null];

            $media = Media::create([
                'name'      => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'path'      => $path,
                'mime_type' => $file->getMimeType(),
                'size'      => $file->getSize(),
                'width'     => $width,
                'height'    => $height,
            ]);

            $uploaded[] = [
                'id'   => $media->id,
                'url'  => $media->url,
                'name' => $media->name,
            ];
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'files' => $uploaded]);
        }

        return back()->with('success', count($uploaded) . ' dosya yüklendi.');
    }

    public function list(Request $request)
    {
        $media = Media::latest()->paginate(40);
        return response()->json([
            'data' => $media->map(fn($m) => [
                'id'         => $m->id,
                'name'       => $m->name,
                'url'        => $m->url,
                'human_size' => $m->human_size,
                'mime_type'  => $m->mime_type,
            ]),
            'next_page_url' => $media->nextPageUrl(),
        ]);
    }

    public function destroy(Media $media)
    {
        Storage::disk('public')->delete($media->path);
        $media->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Dosya silindi.');
    }
}
