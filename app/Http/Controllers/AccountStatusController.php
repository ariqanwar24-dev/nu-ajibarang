<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountStatusController extends Controller
{
    public function show(Request $request): View
    {
        return view('auth.account-status', [
            'user' => $request->user(),
        ]);
    }
}
