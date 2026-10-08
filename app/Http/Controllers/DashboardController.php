<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /** Manage Portfolio page: every portfolio the user owns. */
    public function index(Request $request): View
    {
        $portfolios = $request->user()->portfolios()
            ->withCount(['educations', 'skills', 'projects', 'experiences', 'links'])
            ->latest('updated_at')
            ->get();

        return view('dashboard', compact('portfolios'));
    }
}
