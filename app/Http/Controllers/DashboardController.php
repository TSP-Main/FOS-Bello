<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [];
        if(Auth::user()->role == 1){
            $companies = Company::where('is_enable', 1)->get();

            $data['totalCompanies'] = count($companies);
            
            $totalActive  = $companies->filter(function ($value) {
                return $value->subscription_date > Carbon::today();
            });
            $data['totalActive'] = count($totalActive);

            $totalInActive  = $companies->filter(function ($value) {
                return $value->subscription_date < Carbon::today();
            });
            $data['totalInActive'] = count($totalInActive);

            $data['latestSubscriptions'] = $totalActive->sortByDesc('created_at')->take(5);
        }
        else{
            $orders = Order::get();
        }
        
        return view ('dashboard', $data);
    }
}
