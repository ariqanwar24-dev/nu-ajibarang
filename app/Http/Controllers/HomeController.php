<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Pengurus;

class HomeController extends Controller
{
    public function index()
    {
        $beritaTerbaru = Berita::query()
            ->published()
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        $pengurus = Pengurus::latest()
            ->take(6)
            ->get();

        return view('beranda', compact(
            'beritaTerbaru',
            'pengurus'
        ));
    }
}
