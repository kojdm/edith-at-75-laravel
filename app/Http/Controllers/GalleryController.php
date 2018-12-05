<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $user_id = uniqid('', true);
        session(['user_id' => $user_id]);

        $images = \DB::table('uploads')->get();
        $data = [
            'images' => $images,
        ];

        return view('home')->with($data);
        // return view('test')->with($data);
    }
}
