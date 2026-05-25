<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $routes = [
            'home',
            'about',
            'programs',
            'teachers',
            'gallery',
            'events',
            'blog',
            'contact',
            'register'
        ];

        return response()->view('sitemap', [
            'routes' => $routes
        ])->header('Content-Type', 'text/xml');
    }
}
