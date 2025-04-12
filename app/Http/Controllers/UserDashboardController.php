<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return Inertia::render('Inquiries', [
            'inquiries' => $user->inquiries()->latest()->get(),
        ]);
    }
}
