<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileEntry;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileEntryController extends Controller
{
    public function index()
    {
        $entries = ProfileEntry::where('type', $this->entryType())
            ->orderBy('order')
            ->orderByDesc('id')
            ->get();

        return view('admin.entries.index', [
            'entries' => $entries,
            'labels'  => $this->labels(),
            'routes'  => $this->routePrefix(),
        ]);
    }

    public function create()
    {
        $projects = Project::orderBy('title')->get();

        return view('admin.entries.form', [
            'entry'     => null,
            'projects'  => $projects,
            'labels'    => $this->labels(),
            'routes'    => $this->routePrefix(),
            'selected'  => old('project_ids', []),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        unset($data['pdf'], $data['project_ids']);
        $data['type'] = $this->entryType();
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = (int) ProfileEntry::where('type', $this->entryType())->max('order') + 1;
        $data['pdf'] = $this->storePdf($request);

        $entry = ProfileEntry::create($data);
        $entry->projects()->sync($request->input('project_ids', []));

        return redirect()->route($this->routePrefix() . '.index')
            ->with('success', $this->labels()['singular'] . ' eklendi.');
    }

    public function edit(ProfileEntry $entry)
    {
        abort_unless($entry->type === $this->entryType(), 404);

        $projects = Project::orderBy('title')->get();

        return view('admin.entries.form', [
            'entry'    => $entry,
            'projects' => $projects,
            'labels'   => $this->labels(),
            'routes'   => $this->routePrefix(),
            'selected' => old('project_ids', $entry->projects->pluck('id')->toArray()),
        ]);
    }

    public function update(Request $request, ProfileEntry $entry)
    {
        abort_unless($entry->type === $this->entryType(), 404);

        $data = $this->validated($request);
        unset($data['pdf'], $data['project_ids']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->boolean('remove_pdf') && $entry->pdf) {
            Storage::disk('public')->delete($entry->pdf);
            $data['pdf'] = null;
        }

        $uploaded = $this->storePdf($request);
        if ($uploaded) {
            if ($entry->pdf) {
                Storage::disk('public')->delete($entry->pdf);
            }
            $data['pdf'] = $uploaded;
        }

        $entry->update($data);
        $entry->projects()->sync($request->input('project_ids', []));

        return redirect()->route($this->routePrefix() . '.index')
            ->with('success', $this->labels()['singular'] . ' güncellendi.');
    }

    public function destroy(ProfileEntry $entry)
    {
        abort_unless($entry->type === $this->entryType(), 404);

        if ($entry->pdf) {
            Storage::disk('public')->delete($entry->pdf);
        }
        $entry->delete();

        return redirect()->route($this->routePrefix() . '.index')
            ->with('success', $this->labels()['singular'] . ' silindi.');
    }

    public function reorder(Request $request)
    {
        $ids = $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:profile_entries,id',
        ])['ids'];

        $type = $this->entryType();

        foreach ($ids as $i => $id) {
            ProfileEntry::where('id', $id)->where('type', $type)->update(['order' => $i]);
        }

        return response()->json(['success' => true]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title'        => 'required|string|max:255',
            'title_en'     => 'nullable|string|max:255',
            'subtitle'     => 'nullable|string',
            'subtitle_en'  => 'nullable|string',
            'image'        => 'nullable|string|max:500',
            'external_url' => 'nullable|url|max:500',
            'pdf'          => 'nullable|file|mimes:pdf|max:20480',
            'project_ids'  => 'nullable|array',
            'project_ids.*'=> 'integer|exists:projects,id',
        ]);
    }

    private function storePdf(Request $request): ?string
    {
        if (!$request->hasFile('pdf')) {
            return null;
        }

        return $request->file('pdf')->store('pdfs', 'public');
    }

    private function isPublication(): bool
    {
        return str_contains(request()->route()->getName() ?? '', 'publication');
    }

    private function entryType(): string
    {
        return $this->isPublication() ? 'publication' : 'award';
    }

    private function routePrefix(): string
    {
        return $this->isPublication() ? 'admin.publications' : 'admin.awards';
    }

    private function labels(): array
    {
        return $this->isPublication()
            ? ['singular' => 'Yayın', 'plural' => 'Yayınlar']
            : ['singular' => 'Ödül', 'plural' => 'Ödüller'];
    }
}
