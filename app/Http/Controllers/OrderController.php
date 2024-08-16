<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;
        $data['orders'] = Order::where('company_id', $companyId)->orderBy('id', 'desc')->get();

        return view('orders.list', $data);
    }

    public function detail($id)
    {
        $orderId = base64_decode($id);
        $data['orderDetails'] = Order::with('details')->find($orderId);
        return view('orders.detail', $data);
    }

    public function send_mail() {
        $data = ['name' => "Lana Desert"];
 
        Mail::send([], $data, function($message) {
            $message->to('usmandiljan@gmail.com', 'User')
                    ->subject('Order Status')
                    ->text('This is a test email.');
            $message->from('usman@tahqeeqotajzia.com','Lana Desert');
        });

        echo "Basic Email Sent. Check your inbox.";
     }
}
