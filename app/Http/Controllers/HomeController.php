<?php

namespace App\Http\Controllers;

use App\Models\Banner;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->get();

        return view('home', compact('banners'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function about()
    {
        return view('about-us');
    }
}
