<?php

namespace App\Http\Controllers\Auth;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Override the authenticated method to always redirect to dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Check if the user's company is inactive
        if ($user->role != 1 && $user->company->is_enable == 2) {;
            // Log out the user and redirect to the renewal page
            Auth::logout();

            // return redirect()->route('renewal');

            return redirect()->route('renewal')->with([
                'user_id'       => $user->id,
                'user_name'     => $user->name,
                'company_id'    => $user->company->id,
                'company_name'  => $user->company->name,
                'package'       => $user->company->package,
                'plan'          => $user->company->plan,
            ]);
        }

        return redirect()->route('dashboard');
    }
}
