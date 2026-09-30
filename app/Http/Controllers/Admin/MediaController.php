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
        $folders = \DB::table('media_folders')->orderBy('name')->pluck('name');
        $folder  = $request->get('folder');

        $query = Media::orderBy('order')->orderBy('name');
        if ($folder) {
            $query->where('folder', $folder);
        }

        $media = $query->paginate(60)->withQueryString();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'data' => $media->map(fn($m) => [
                    'id'         => $m->id,
                    'url'        => $m->url,
                    'name'       => $m->name,
                    'human_size' => $m->human_size,
                ]),
                'next_page_url' => $media->nextPageUrl(),
            ]);
        }

        return view('admin.media.index', compact('media', 'folders', 'folder'));
    }

    public function createFolder(Request $request)
    {
        $name = trim($request->input('name', ''));
        if (!$name) {
            return response()->json(['success' => false, 'message' => 'Klasör adı boş olamaz.'], 422);
        }
        \DB::table('media_folders')->insertOrIgnore(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
        return response()->json(['success' => true, 'name' => $name]);
    }

    public function store(Request $request)
    {
        // PHP max_file_uploads limitini artır
        @ini_set('max_file_uploads', 200);

        $request->validate([
            'files.*' => 'required|file|mimes:jpg,jpeg,png,gif,webp,svg|max:8192',
        ]);

        $folder   = $request->input('folder') ?: null;
        $uploaded = [];

        $files = $request->file('files', []);

        // İsme göre sırala
        usort($files, fn($a, $b) => strcmp($a->getClientOriginalName(), $b->getClientOriginalName()));

        foreach ($files as $file) {
            $path     = $file->store('media', 'public');
            $fullPath = storage_path('app/public/' . $path);

            [$width, $height] = @getimagesize($fullPath) ?: [null, null];

            $media = Media::create([
                'name'      => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'folder'    => $folder,
                'path'      => $path,
                'mime_type' => $file->getMimeType(),
                'size'      => $file->getSize(),
                'width'     => $width,
                'height'    => $height,
            ]);

            $uploaded[] = [
                'id'     => $media->id,
                'url'    => $media->url,
                'name'   => $media->name,
                'folder' => $media->folder,
            ];
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'files' => $uploaded]);
        }

        return back()->with('success', count($uploaded) . ' dosya yüklendi.');
    }

    public function list(Request $request)
    {
        $folder = $request->get('folder');
        $page   = max(1, (int) $request->get('page', 1));

        $query = Media::orderBy('name');
        if ($folder) {
            $query->where('folder', $folder);
        }

        $paginated = $query->paginate(40, ['*'], 'page', $page);

        return response()->json([
            'data' => $paginated->map(fn($m) => [
                'id'         => $m->id,
                'name'       => $m->name,
                'folder'     => $m->folder,
                'url'        => $m->url,
                'human_size' => $m->human_size,
                'mime_type'  => $m->mime_type,
            ]),
            'current_page'  => $paginated->currentPage(),
            'last_page'     => $paginated->lastPage(),
            'next_page_url' => $paginated->nextPageUrl(),
        ]);
    }

    public function folders()
    {
        $folders = \DB::table('media_folders')->orderBy('name')->pluck('name');
        return response()->json($folders);
    }

    public function move(Request $request)
    {
        $ids    = $request->input('ids', []);
        $folder = $request->input('folder'); // null = kök dizin
        if ($ids) {
            Media::whereIn('id', $ids)->update(['folder' => $folder ?: null]);
        }
        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        $ids = $request->input('ids', []);
        foreach ($ids as $i => $id) {
            Media::where('id', $id)->update(['order' => $i]);
        }
        return response()->json(['success' => true]);
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
