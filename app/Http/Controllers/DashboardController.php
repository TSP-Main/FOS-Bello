<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                return $value->expiry_date > Carbon::today();
            });
            $data['totalActive'] = count($totalActive);

            $totalInActive  = $companies->filter(function ($value) {
                return $value->expiry_date < Carbon::today();
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
            $data['revenue'] = $this->seven_days_revenue($companyId);
            $data['chartData'] = $data['revenue']['chartData'];

        }
        // return $data['revenue'];
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

    public function seven_days_revenue($companyId)
    {
        // Last 7 Days Revenue
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        
        $orders = Order::where('company_id', $companyId)
                ->where('order_status', config('constants.DELIVERED'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

        $lastSevenDaysRevenue = $orders->sum('total');

        // Revenue for the previous 7 days before the last 7 days
        $previousEndDate = $startDate->copy()->subDay()->endOfDay(); 
        $previousStartDate = $previousEndDate->copy()->subDays(6)->startOfDay();

        $previousOrders = Order::where('company_id', $companyId)
            ->where('order_status', config('constants.DELIVERED'))
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->get();

        $previousSevenDaysRevenue = $previousOrders->sum('total');

        // Calculate the percentage change
        $percentage = 0;
        if ($previousSevenDaysRevenue > 0) {
            $percentage = (($lastSevenDaysRevenue - $previousSevenDaysRevenue) / $previousSevenDaysRevenue) * 100;
        }

        // Data for Chart
        $dates = [];
        $revenues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dates[] = Carbon::parse($date)->format('D');
            $dailyRevenue = $orders->filter(function ($order) use ($date) {
                return Carbon::parse($order->created_at)->format('Y-m-d') === $date;
            })->sum('total');
            $revenues[] = $dailyRevenue;
        }

        return [
            'lastSevenDaysRevenue' => $lastSevenDaysRevenue,
            'percentage' => $percentage,
            'chartData' => [
                'categories' => $dates,
                'series' => $revenues,
            ]
        ];
    }
}
