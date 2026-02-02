<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function leave()
    {
        if (!session()->has('impersonator_id')) {
            return redirect()->route('dashboard');
        }

        $superAdmin = User::findOrFail(session()->pull('impersonator_id'));

        Auth::login($superAdmin);

        return redirect()->route('super-admin.dashboard');
    }
}
