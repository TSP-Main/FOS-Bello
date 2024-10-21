<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\Order;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Primary #1EABAE
        // Secondary #F8A61B

        $data['patners'] = Company::where('status', 1)->count();
        $data['users'] = Order::distinct()->count('email');
        
        return view('landing-page.home', $data);
    }
}
