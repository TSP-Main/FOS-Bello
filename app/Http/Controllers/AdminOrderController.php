<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TemporaryOrder;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\DB;
use App\Models\TemporaryOrderDetail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = TemporaryOrder::where('status', 'pending')->orderBy('id', 'desc')->get();
        return view('orders.temporary', compact('orders'));
    }
    
    public function approve(Request $request, $id)
    {
        $temporaryOrder = TemporaryOrder::findOrFail($id);
        $deliveryTime = $request->input('delivery_time');
    
        // Move to main orders table
        $order = new Order();
        $order->company_id = $temporaryOrder->company_id;
        $order->name = $temporaryOrder->name;
        $order->email = $temporaryOrder->email;
        $order->phone = $temporaryOrder->phone;
        $order->address = $temporaryOrder->address;
        $order->total = $temporaryOrder->total;
        $order->order_type = $temporaryOrder->order_type;
        $order->payment_option = $temporaryOrder->payment_option;
        $order->deliver_time = $deliveryTime;
        $order->save();
        $orderId = $order->id;
    
        // Move order details
        $orderDetails = TemporaryOrderDetail::where('temporary_order_id', $temporaryOrder->id)->get();
        foreach ($orderDetails as $detail) {
            $orderDetail = new OrderDetail();
            $orderDetail->order_id = $orderId;
            $orderDetail->product_id = $detail->product_id;
            $orderDetail->product_title = $detail->product_title;
            $orderDetail->product_price = $detail->product_price;
            $orderDetail->quantity = $detail->quantity;
            $orderDetail->sub_total = $detail->sub_total;
            $orderDetail->options = $detail->options;
            $orderDetail->item_instruction = $detail->item_instruction;
            $orderDetail->save();
        }
    
        //update order id in transaction table 
        if($temporaryOrder->payment_option == 'online'){
            $transaction_data['order_id'] = $orderId;
            $transaction_data['order_status'] = 1; // order accepted status
            $transaction = Transaction::where('temp_order_id', $id)->first();
            $transaction->update($transaction_data);    
        }

        // Delete temporary order and details
        $temporaryOrder->delete();
        TemporaryOrderDetail::where('temporary_order_id', $id)->delete();
    
        // Delete related notifications
        $this->deleteRelatedNotifications($temporaryOrder->company_id, $id);
    
        // Send mail to user if email is entered
        if ($temporaryOrder->email) {
            $data = ['name' => "Lana Desert"];
    
            Mail::send([], $data, function($message) {
                $message->to('usmandiljan@gmail.com', 'User')
                        ->subject('Order Status')
                        ->text('Your Order Number is this. which is accepted');
                $message->from('usman@tahqeeqotajzia.com', 'Lana Desert');
            });
        }
    
        // Generate PDF receipt
        $pdf = PDF::loadView('orders.reciept', ['order' => $order]);
    
        // Define the path to store the PDF
        $pdfPath = 'receipts/order_' . $orderId . '.pdf';
    
        // Store the PDF in the storage directory
        Storage::put($pdfPath, $pdf->output());
    
        return redirect()->route('orders.list')->with('status', 'Order approved successfully');
    }
    
    private function deleteRelatedNotifications($companyId, $temporaryOrderId)
    {
        DB::table('notifications')
            ->whereRaw('JSON_EXTRACT(data, "$.order_id") = ?', [$temporaryOrderId])
            ->whereRaw('JSON_EXTRACT(data, "$.company_id") = ?', [$companyId])
            ->delete();
    }
    
    public function reject($id)
    {
        $temporaryOrder = TemporaryOrder::findOrFail($id);

        //update transaction table 
        if($temporaryOrder->payment_option == 'online'){
            $transaction_data['order_status'] = 2; // order rejected status;
            $transaction = Transaction::where('temp_order_id', $id)->first();
            $transaction->update($transaction_data);
        }

        // Delete temporary order and details
        TemporaryOrderDetail::where('temporary_order_id', $id)->delete();
        $temporaryOrder->delete();

        // send mail to user if email is enterd
        if($temporaryOrder->email){
            $data = ['name' => "Lana Desert"];
            Mail::send([], $data, function($message) {
                $message->to('usmandiljan@gmail.com', 'User')
                        ->subject('Order Status')
                        ->text('Your Order is rejected');
                $message->from('usman@tahqeeqotajzia.com','Lana Desert');
            });
        }

        // return redirect()->route('orders.noti')->with('status', 'Order rejected successfully');
        return redirect()->route('orders.list')->with('status', 'Order rejected successfully');
    }
}

