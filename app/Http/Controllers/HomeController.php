<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Support\SamplePortfolio;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home');
    }

    /** A template rendered with sample data, for the home page sheets. */
    public function sample(string $template): View
    {
        abort_unless(array_key_exists($template, Portfolio::TEMPLATES), 404);

        return view('templates.'.$template, [
            'p' => SamplePortfolio::make($template),
            'embedded' => true,
            'owner' => false,
        ]);
    }
}
