<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ContactMessage;
use App\Models\TeamMember;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'projects'  => Project::count(),
            'messages'  => ContactMessage::where('is_read', false)->count(),
            'team'      => TeamMember::count(),
            'categories'=> Category::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
