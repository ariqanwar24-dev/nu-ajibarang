<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Pengurus;

class HomeController extends Controller
{
    public function index()
    {
        $beritaTerbaru = Berita::latest()
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
