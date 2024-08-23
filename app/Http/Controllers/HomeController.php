<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function showHome()
    {
        $user = Auth::user(); // Get the authenticated user

        if (!$user) {
            // Handle the case where there is no authenticated user
            return redirect()->route('login');
        }

        return view('home', ['user' => $user]);
    }
}