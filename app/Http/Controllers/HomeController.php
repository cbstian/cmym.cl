<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
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
