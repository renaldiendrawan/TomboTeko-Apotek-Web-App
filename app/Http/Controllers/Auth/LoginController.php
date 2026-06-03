<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Arahkan semua user (termasuk pelanggan) ke dalam sistem setelah login
     */
    protected $redirectTo = '/home'; // Atau ubah ke '/dashboard' jika route Anda bernama dashboard

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}