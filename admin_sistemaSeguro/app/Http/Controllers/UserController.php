<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $uploads = $user->uploads()->orderBy('created_at', 'desc')->get();
        return view('user.dashboard', compact('uploads'));
    }
}
