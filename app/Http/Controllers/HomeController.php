<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

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
        // #1eabae primary color
        // #f8a61b secondary color
        return view('landing-page.home');
    }
    
    public function register(Request $request)
    {
        $this->validate($request, [
            'owner_name'      => 'required',
            'restaurant_name'     => 'required',
            'email'   => 'required|email',
            'phone' => 'required',
        ]);

        $company = new Company();
        $company->owner_name = $request->owner_name;
        $company->name = $request->restaurant_name;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->status = 2;
        $response = $company->save();

        return redirect()->route('register')->with('success', 'Signup successfully! We will contact you soon');
    }
}
