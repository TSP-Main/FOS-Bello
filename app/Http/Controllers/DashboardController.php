<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [];
        if(Auth::user()->role == 1){
            $companies = Company::where('status', 1)->get();

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
            $companyId = Auth::user()->company_id; 
            $todayStart = Carbon::today()->startOfDay();
            $todayEnd = Carbon::today()->endOfDay();

            $orders = Order::where('company_id', $companyId)
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->get();
                
            $data['dashboard_data'] = $this->make_dashboard_data($orders);

        }
        // return $data;
        return view ('dashboard', $data);
    }

    public function make_dashboard_data($orders)
    {
        $totalOrders = count($orders);

        $totalDelivered  = $orders->filter(function ($value) {
            return $value->order_status == config('constants.DELIVERED');
        });

        $totalCancelled  = $orders->filter(function ($value) {
            return $value->order_status == config('constants.CANCELED');
        });
        // $data['totalCancelled'] = count($totalCancelled);

        $totalRevenue = $totalDelivered->sum('total');

        return [
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalDelivered' => count($totalDelivered),
            'totalCancelled' => count($totalCancelled),
        ];
    }

    public function filter(Request $request)
    {
        $companyId = Auth::user()->company_id; 
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Order::where('company_id', $companyId);

        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $orders = $query->get();

        $response = $this->make_dashboard_data($orders);
        
        return response()->json(['stats' => $response]);
    }
}
