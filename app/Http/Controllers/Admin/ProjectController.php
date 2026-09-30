<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectDesigner;
use App\Models\ProjectTeam;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('categories')->orderBy('order')->orderByDesc('id')->paginate(20);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $categories = Category::orderBy('order')->orderBy('name')->where('is_active', true)->get();
        return view('admin.projects.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'              => 'required|string|max:255',
            'title_en'           => 'nullable|string|max:255',
            'subtitle'           => 'nullable|string|max:255',
            'subtitle_en'        => 'nullable|string|max:255',
            'description'        => 'nullable|string',
            'description_en'     => 'nullable|string',
            'category_ids'       => 'nullable|array',
            'category_ids.*'     => 'exists:categories,id',
            'location'           => 'nullable|string|max:255',
            'client'             => 'nullable|string|max:255',
            'land_area'          => 'nullable|string|max:255',
            'construction_area'  => 'nullable|string|max:255',
            'meta_text'          => 'nullable|string',
            'meta_text_en'       => 'nullable|string',
            'year'               => 'nullable|integer|min:1900|max:2100',
            'video_url'          => 'nullable|url|max:255',
            'cover_image'        => 'nullable|string|max:500',
            'order'              => 'nullable|integer',
        ]);

        $data['slug'] = $this->uniqueSlug(Str::slug($data['title']));
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['order'] = $data['order'] ?? 0;

        $categoryIds = $data['category_ids'] ?? [];
        unset($data['category_ids']);

        $project = Project::create($data);
        $project->categories()->sync($categoryIds);

        $this->syncImages($project, $request->input('slider_paths', []), 'slider');
        $this->syncImages($project, $request->input('gallery_paths', []), 'gallery');
        $this->syncDesigners($project, $request->input('designers', []));
        $this->syncTeam($project, $request->input('team', []));

        return redirect()->route('admin.projects.index')->with('success', 'Proje eklendi.');
    }

    public function edit(Project $project)
    {
        $project->load('categories', 'sliderImages', 'galleryImages', 'designers', 'team');
        $categories = Category::orderBy('order')->orderBy('name')->where('is_active', true)->get();
        return view('admin.projects.form', compact('project', 'categories'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title'              => 'required|string|max:255',
            'title_en'           => 'nullable|string|max:255',
            'subtitle'           => 'nullable|string|max:255',
            'subtitle_en'        => 'nullable|string|max:255',
            'description'        => 'nullable|string',
            'description_en'     => 'nullable|string',
            'category_ids'       => 'nullable|array',
            'category_ids.*'     => 'exists:categories,id',
            'location'           => 'nullable|string|max:255',
            'client'             => 'nullable|string|max:255',
            'land_area'          => 'nullable|string|max:255',
            'construction_area'  => 'nullable|string|max:255',
            'meta_text'          => 'nullable|string',
            'meta_text_en'       => 'nullable|string',
            'year'               => 'nullable|integer|min:1900|max:2100',
            'video_url'          => 'nullable|url|max:255',
            'cover_image'        => 'nullable|string|max:500',
            'order'              => 'nullable|integer',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['order'] = $data['order'] ?? 0;

        $categoryIds = $data['category_ids'] ?? [];
        unset($data['category_ids']);

        $project->update($data);
        $project->categories()->sync($categoryIds);

        $this->syncImages($project, $request->input('slider_paths', []), 'slider');
        $this->syncImages($project, $request->input('gallery_paths', []), 'gallery');
        $this->syncDesigners($project, $request->input('designers', []));
        $this->syncTeam($project, $request->input('team', []));

        return redirect()->route('admin.projects.edit', $project)->with('success', 'Proje güncellendi.');
    }

    public function destroy(Project $project)
    {
        $project->images()->delete();
        $project->designers()->delete();
        $project->team()->delete();
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Proje silindi.');
    }

    private function syncImages(Project $project, array $paths, string $type): void
    {
        $project->images()->where('type', $type)->delete();

        foreach (array_values(array_filter($paths)) as $order => $path) {
            ProjectImage::create([
                'project_id' => $project->id,
                'image'      => $path,
                'type'       => $type,
                'order'      => $order,
            ]);
        }

        // cover_image: manuel seçilmişse onu kullan, seçilmemişse slider/galeri'den ata
        $manualCover = request()->input('cover_image');
        if ($manualCover) {
            $project->update(['cover_image' => $manualCover]);
        } else {
            $cover = $project->images()->where('type', 'slider')->orderBy('order')->first()
                ?? $project->images()->where('type', 'gallery')->orderBy('order')->first();
            if ($cover) {
                $project->update(['cover_image' => $cover->image]);
            }
        }
    }

    private function syncDesigners(Project $project, array $names): void
    {
        $project->designers()->delete();
        foreach (array_values(array_filter($names)) as $order => $name) {
            ProjectDesigner::create([
                'project_id' => $project->id,
                'name'       => $name,
                'order'      => $order,
            ]);
        }
    }

    private function syncTeam(Project $project, array $names): void
    {
        $project->team()->delete();
        foreach (array_values(array_filter($names)) as $order => $name) {
            ProjectTeam::create([
                'project_id' => $project->id,
                'name'       => $name,
                'order'      => $order,
            ]);
        }
    }

    private function uniqueSlug(string $base): string
    {
        $slug = $base;
        $i = 1;
        while (Project::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }
}
