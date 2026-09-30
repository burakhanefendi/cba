<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function index()
    {
        $members = TeamMember::orderBy('order')->orderBy('name')->get();
        return view('admin.team.index', compact('members'));
    }

    public function create()
    {
        return view('admin.team.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? 0;

        TeamMember::create($data);

        return redirect()->route('admin.team.index')->with('success', 'Ekip üyesi eklendi.');
    }

    public function edit(TeamMember $team)
    {
        $member = $team;
        return view('admin.team.edit', compact('member'));
    }

    public function update(Request $request, TeamMember $team)
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? 0;

        $team->update($data);

        return redirect()->route('admin.team.index')->with('success', 'Ekip üyesi güncellendi.');
    }

    public function destroy(TeamMember $team)
    {
        $team->delete();
        return redirect()->route('admin.team.index')->with('success', 'Ekip üyesi silindi.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'     => 'required|string|max:255',
            'title'    => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'bio'      => 'nullable|string',
            'bio_en'   => 'nullable|string',
            'photo'    => 'nullable|string|max:500',
            'order'    => 'nullable|integer',
        ]);
    }
}
