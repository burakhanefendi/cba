<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\News;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'projects'   => Project::count(),
            'categories' => Category::count(),
            'news'       => News::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
