<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function repeated_customers_list()
    {
        $companyId = Auth::user()->company_id;
        $data['customers'] = Order::select('email', DB::raw('COUNT(*) as order_count'))
            ->where('company_id', $companyId)
            ->whereNotNull('email')
            ->groupBy('email')
            ->having('order_count', '>', 1)
            ->get()
            ->makeHidden(['formatted_created_at', 'formatted_updated_at']);
        
        return view('customers.repeated_customers', $data);
    }
}
