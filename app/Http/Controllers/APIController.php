<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Company;
use App\Models\Product;
use App\Models\Category;
use App\Models\Discount;
use App\Models\OptionValue;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\TemporaryOrder;
use Illuminate\Http\JsonResponse;
use App\Models\RestaurantSchedule;
use App\Models\TemporaryOrderDetail;
use Illuminate\Support\Facades\Crypt;
use App\Models\NewsletterSubscription;
use App\Models\RestaurantStripeConfig;

class APIController extends Controller
{
    public function restaurant_detail(Request $request)
    {
        $response = validate_token($request->header('Authorization'));
        $responseData = $response->getData();

        $data = [
            'address' => $responseData->company->address,
            'apartment' => $responseData->company->apartment,
            'city' => $responseData->company->city,
            'postcode' => $responseData->company->postcode,
            'radius' => $responseData->company->radius,
            'latitude' => $responseData->company->latitude,
            'longitude' => $responseData->company->longitude,
            'amount' => $responseData->company->free_shipping_amount,
            'currency' => $responseData->company->currency,
            'currency_symbol' => $responseData->company->currency_symbol,
        ];

        if($responseData->status == 'success'){
            return response()->json(['status' => 'success', 'message' => 'Products Found', 'data' => $data], 200);
        }
        else{
            return response()->json(['status' => $responseData->status, 'message' => $responseData->message], 401);
        }
    }

    public function categories(Request $request): JsonResponse
    {
        try {
            // Retrieve token from Authorization header
            $token = $request->header('Authorization');

            // Find company by token
            $company = Company::where('token', $token)->first();

            // Check if company exists
            if (!$company) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized access'], 401);
            }

            // Retrieve categories belonging to the authenticated company
            $categories = Category::where('status', '1')
                                  ->where('company_id', $company->id)
                                  ->orderByRaw("ISNULL(sort_order), sort_order ASC")
                                  ->get();

            // Check if categories found
            if ($categories->isEmpty()) {
                return response()->json(['status' => 'empty', 'message' => 'No active categories found for your company'], 404);
            }

            
            $categoryData = $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'attributes' => [
                        'name' => $category->name,
                        'desc' => $category->desc,
                        'type' => $category->type,
                        'icon_file' => $category->icon_file,
                        'background_image' => $category->background_image,
                        'slug' => $category->slug,
                        'status' => $category->status,
                        'sort_order' => $category->sort_order,
                        'created_at' => $category->created_at,
                        'updated_at' => $category->updated_at,
                        'parent_id' => $category->parent_id,
                        'company_id' => $category->company_id,
                        'created_by' => $category->created_by,
                        'updated_by' => $category->updated_by,
                    ],
                ];
            });

            // Prepare CORS headers
            $headers = [
                'Access-Control-Allow-Origin' => 'http://127.0.0.1:8001',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            ];

            return response()->json(['status' => 'success', 'message' => 'Active categories for your company', 'data' => $categoryData], 200, $headers);

        } catch (\Exception $e) {
            
            return response()->json(['status' => 'error', 'message' => 'Error retrieving categories', 'error' => $e->getMessage()], 500);
        }
    }

    public function menu(Request $request)
    {
        $response = validate_token($request->header('Authorization'));
        $responseData = $response->getData();

        if($responseData->status == 'success'){
            $companyId = $responseData->company->id;
            $menu = Menu::with('category','product')->where('company_id', $companyId)->where('is_enable', 1)->get();

            return response()->json(['status' => 'success', 'message' => 'Menu Found', 'data' => $menu], 200);
        }
        else{
            return response()->json(['status' => $responseData->status, 'message' => $responseData->message], 401);
        }
    }

    public function products(Request $request, $id = null)
    {
        // fetch  single or all products of a company
        $response = validate_token($request->header('Authorization'));
        $responseData = $response->getData();

        if($responseData->status == 'success'){
            $companyId = $responseData->company->id;
            $products = Product::with('category', 'images', 'options.option.option_values')
                ->where('company_id', $companyId)
                ->where('is_enable', 1)
                ->when($id, function($query, $id){
                    return $query->where('id', $id);
                })
                ->get();

            return response()->json(['status' => 'success', 'message' => 'Products Found', 'data' => $products], 200);
        }
        else{
            return response()->json(['status' => $responseData->status, 'message' => $responseData->message], 401);
        }
    }

    public function category_products(Request $request, $category = null)
    {
        // Fetch prdocuts of a specific category
        $response = validate_token($request->header('Authorization'));
        $responseData = $response->getData();

        if($responseData->status == 'success'){
            if($category){
                $categoryDetail = Category::where('slug', $category)->where('status', 1)->first();
                if($categoryDetail){
                    $companyId = $responseData->company->id;
                    $products = Product::with('category', 'images', 'options.option.option_values')->where('company_id', $companyId)->where('category_id', $categoryDetail->id)->where('is_enable', 1)->get();
                    return response()->json(['status' => 'success', 'message' => 'Products Found', 'data' => $products, 'categoryDetail' => $categoryDetail], 200);
                }
                else{
                    return response()->json(['status' => 'error', 'message' => 'Category is disable', 'data' => ''], 404);
                }
            }
            else{
                return response()->json(['status' => 'error', 'message' => 'Kindly provide category', 'data' => ''], 404);
            }
        }
        else{
            return response()->json(['status' => $responseData->status, 'message' => $responseData->message], 401);
        }
    }

    public function schedule(Request $request)
    {
        $response = validate_token($request->header('Authorization'));
        $responseData = $response->getData();

        if($responseData->status == 'success'){
            $companyId = $responseData->company->id;
            $data['schedule'] = RestaurantSchedule::where('company_id', $companyId)->get();
            $data['timezone'] = Company::where('id', $companyId)->pluck('timezone');

            return response()->json(['status' => 'success', 'message' => 'Schedule Found', 'data' => $data], 200);
        }
        else{
            return response()->json(['status' => $responseData->status, 'message' => $responseData->message], 401);
        }
    }

    public function categories_a(Request $request)
    {
        $response = validate_token($request->header('Authorization'));
        
        $responseData = $response->getData();

        if($responseData->status == 'success'){
            $companyId = $responseData->company->id;
            $categories = Category::where('company_id', $companyId)->where('status', 1)->orderByRaw("ISNULL(sort_order), sort_order ASC")->get();

            return response()->json(['status' => 'success', 'message' => 'Categories List', 'data' => $categories], 200);
        }
        else{
            return response()->json(['status' => $responseData->status, 'message' => $responseData->message], 401);
        }
    }

    public function get_option_value_detail(Request $request)
    {
        $response = validate_token($request->header('Authorization'));
        
        $responseData = $response->getData();

        if($responseData->status == 'success'){
            $companyId = $responseData->company->id;
            $options = OptionValue::whereIn('id', $request)->get();

            return response()->json(['status' => 'success', 'message' => 'Options List', 'data' => $options], 200);
        }
        else{
            return response()->json(['status' => $responseData->status, 'message' => $responseData->message], 401);
        }
    }

    public function order_process(Request $request)
    {
        $response = validate_token($request->header('Authorization'));
        $responseData = $response->getData();

        if($responseData->status == 'success'){
            $order = new Order();

            $order->company_id      = $responseData->company->id;
            $order->name            = $request->name;
            $order->email           = $request->email;
            $order->phone           = $request->phone;
            $order->address         = $request->address;
            $order->total           = $request->cartTotal;
            $order->order_type      = $request->orderType;
            $order->payment_option  = $request->paymentOption;
            $order->order_note      = $request->orderNote;

            $order->save();
            $orderId = $order->id;

            if($orderId){
                $orderItems = $request->cartItems;
                foreach($orderItems as $orderItem){
                    $orderDetail = new OrderDetail();

                    $orderDetail->order_id = $orderId;
                    $orderDetail->product_id = $orderItem['productId'];
                    $orderDetail->product_title = $orderItem['productTitle'];
                    $orderDetail->product_price = $orderItem['productPrice'];
                    $orderDetail->quantity = $orderItem['quantity'];
                    $orderDetail->sub_total = $orderItem['rowTotal'];
                    $orderDetail->options = implode(',', $orderItem['optionNames']);

                    $orderDetail->save();
                }
            }
    
            return response()->json(['status' => 'success', 'message' => 'Order Placed Successfully', 'orderId' => $orderId], 200);
        } else {
            return response()->json(['status' => $responseData->status, 'message' => $responseData->message], 401);
        }
    }

    public function stripe_config(Request $request)
    {
        $response = validate_token($request->header('Authorization'));
        $responseData = $response->getData();

        if($responseData->status == 'success'){
            $companyId = $responseData->company->id;
            $stripeConfig = RestaurantStripeConfig::where('company_id', $companyId)->first();
            $data['stripeKey'] = Crypt::decrypt($stripeConfig->stripe_key);

            return response()->json(['status' => 'success', 'message' => 'Found', 'data' => $data], 200);
        }
        else{
            return response()->json(['status' => $responseData->status, 'message' => $responseData->message], 401);
        }
    }

    public function newsletter_subscribe(Request $request)
    {
        $response = validate_token($request->header('Authorization'));
        $responseData = $response->getData();
        $companyId = $responseData->company->id;

        if($responseData->status == 'success'){

            $subscription = new NewsletterSubscription();

            $subscription->company_id = $companyId;
            $subscription->email = $request->email;
            
            $subscription->save();

            return response()->json(['status' => 'success', 'message' => 'Thanks for subscription'], 200);
        }
        else{
            return response()->json(['status' => $responseData->status, 'message' => $responseData->message], 401);
        }
    }

    public function discount_check(Request $request)
    {
        $response = validate_token($request->header('Authorization'));
        $responseData = $response->getData();
        $companyId = $responseData->company->id;

        if($responseData->status == 'success'){
            $code = strtoupper(trim($request->code));
            $discountDetail = Discount::where('company_id', $companyId)
            ->where('code', $code)
            ->where('expiry', '>', now())
            ->first();

            if($discountDetail){
                $data['type'] = $discountDetail->type;
                $data['rate'] = $discountDetail->rate;
                $data['minimum_amount'] = $discountDetail->minimum_amount;
                
                return response()->json(['status' => 'success', 'message' => 'Found', 'data' => $data], 200);
            }
            else{
                return response()->json(['status' => 'fail', 'message' => 'Invalid or Expire Code'], 404);
            }
        }
        else{
            return response()->json(['status' => $responseData->status, 'message' => $responseData->message], 401);
        }
    }

    public function products_search(Request $request)
    {
        // fetch  single or all products of a company
        $response = validate_token($request->header('Authorization'));
        $responseData = $response->getData();

        if($responseData->status == 'success'){
            $companyId = $responseData->company->id;
            $products = Product::where('company_id', $companyId)
                ->where('is_enable', 1)
                ->where('title', 'LIKE', '%' . $request->title . '%')
                ->get();

            return response()->json(['status' => 'success', 'message' => 'Products Found', 'data' => $products], 200);
        }
        else{
            return response()->json(['status' => $responseData->status, 'message' => $responseData->message], 401);
        }
    }

    public function customers(Request $request)
    {
        // fetch total cutomers sum
        $response = validate_token($request->header('Authorization'));
        $responseData = $response->getData();

        try{
            if($responseData->status == 'success'){
                $companyId = $responseData->company->id;
                $customers = Order::distinct()->count('email');

                return response()->json(['status' => 'success', 'message' => 'Customers Data', 'data' => $customers], 200);
            }
            else{
                return response()->json(['status' => $responseData->status, 'message' => $responseData->message], 401);
            }
        }
        catch (Exception $e){
            return response()->json([
                'status' => 'error', 
                'message' => 'An error occurred while fetching customers data.', 
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
