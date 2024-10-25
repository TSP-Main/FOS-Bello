<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\CompanyTransaction;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CompanyController extends Controller
{
    public function index()
    {
        $data['companies'] = Company::whereIn('status', [1,2])->get();

        return view('companies.list', $data);
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required',
            'email'     => 'required|email',
            'address'   => 'required',
            'expiry_date' => 'required',
            'status' => 'required|in:1,2',
        ]);

        // Generate a unique token
        $token = 'tspkeyusmkeyanikey_apikeypunkeychar' . Str::random(60);
        $company             = new Company();
        $company->name       = $request->name;
        $company->email      = $request->email;
        $company->address    = $request->address;
        $company->expiry_date = $request->expiry_date;
        $company->status     = $request->status;
        $company->token      = $token;
        $company->created_by = Auth::user()->id;
        $company->accepted_date = Carbon::now();
        $response = $company->save();

        return redirect()->route('companies.list')->with('success', 'Company created successfully');
    }

    public function edit($id)
    {
        $data['company'] = Company::find(base64_decode($id));

        return view('companies.edit', $data);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            // 'address' => 'required',
            'expiry_date' => 'required',
            'status' => 'required'
        ]);

        if ($request->id) {
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            // $data['address'] = $request->address;
            $data['expiry_date'] = $request->expiry_date;
            $data['status'] = $request->status;
            $data['is_enable'] = $request->status;
            $data['updated_by'] = Auth::id();

            $company = Company::find(base64_decode($request->id));
            $response = $company->update($data);

            return redirect()->route('companies.list')->with('success', 'Company details updated successfully!');
        }

        return redirect()->route('companies.list')->with('error', 'No company found!');
    }

    public function destroy($id)
    {
    }

    public function refreshToken($id)
    {
        $companyId = base64_decode($id);
        $company = Company::find($companyId);

        if (!$company) {
            return redirect()->route('companies.list')->with('error', 'Company not found!');
        }

        // Generate a new unique token
        $newToken = Str::random(60);
        $company->token = $newToken;
        $company->save();

        return response()->json(['success' => true, 'newToken' => $newToken]);
    }

    public function register(Request $request)
    {
        // Register restaurant by self on landing page
        $this->validate($request, [
            'owner_name' => 'required',
            'restaurant_name' => 'required',
            'email'   => 'required|email',
            'phone' => 'required',
            'package' => 'required|in:1,2,3',
            'plan' => 'required|in:1,2',
            'payment_method' => 'required|string',
        ]);

        $company = new Company();
        $company->owner_name = $request->owner_name;
        $company->name = $request->restaurant_name;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->package = $request->package;
        $company->plan = $request->plan;
        $company->payment_method_id = $request->payment_method;
        $company->status = config('constants.INCOMING_RESTAURANT');
        $response = $company->save();

        return redirect()->route('register')->with('success', 'Signup successfully! We will contact you soon');
    }

    public function incoming_request()
    {
        $requests = Company::get();
        
        $data['incomingRequests'] = $requests->filter(function ($value){
            return $value->status == config('constants.INCOMING_RESTAURANT');
        });

        $data['rejectedRequests'] = $requests->filter(function ($value){
            return $value->status == config('constants.REJECTED_RESTAURANT');
        });

        return view('companies.incoming_request', $data);
    }

    public function incoming_request_action(Request $request, $id)
    {
        $id = base64_decode($id);
        $company = Company::find($id);

        if(in_array($request['action'], ['accept', 'reject'])){
            if ($request['action'] == 'accept') {
                Stripe::setApiKey(env('STRIPE_SECRET'));

                try {
                    // Calculate the amount to charge based on the package and plan
                    $amount = $this->calculateAmount($company->package, $company->plan);
            
                    // Create a payment intent and charge the customer
                    $paymentIntent = PaymentIntent::create([
                        'amount' => $amount * 100, // Amount in cents
                        'currency' => 'gbp',
                        'payment_method' => $company->payment_method_id,
                        'confirm' => true,
                        'automatic_payment_methods' => [
                            'enabled' => true,
                            'allow_redirects' => 'never',
                        ],
                    ]);

                    // Add transation data
                    CompanyTransaction::create([
                        'company_id'    => $company->id,
                        'package'       => $company->package,
                        'plan'          => $company->plan,
                        'amount'        => $amount,
                        'status'        => 'New',
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
            
                    $company->status = config('constants.ACTIVE_RESTAURANT');
                    $company->accepted_date = Carbon::now();

                    $route      = 'companies.list';
                    $msg        = 'New Restaurant Added.';
                    $msgStatus  = 'success';
            
                } catch (\Exception $e) {
                    // Handle payment failure
                    return back()->withErrors('Payment failed: ' . $e->getMessage());
                }
            } 
            elseif ($request['action'] == 'reject') {
                $company->status = config('constants.REJECTED_RESTAURANT');

                $route      = 'companies.incoming.list';
                $msg        = 'Restaurant request rejected.';
                $msgStatus  = 'warning';
            }

            $company['updated_by'] = Auth::user()->id;
            $response = $company->update();

            return redirect()->route($route)->with($msgStatus, $msg);
        }
        
        return redirect()->route('companies.incoming.list')->with('error', 'Data not correct');
    }

    public function check_expiry()
    {
        // update company status to in active when expriy date end
        $today = Carbon::today();
        Company::where('expiry_date', '<=', $today->toDateString())
            ->where('status', config('constants.ACTIVE_RESTAURANT'))
            ->update(['status' => config('constants.IN_ACTIVE_RESTAURANT'), 'is_enable' => 2]);

        return response()->json(['message' => 'Expired restaurants updated to inactive.']);
    }

    private function calculateAmount($package, $plan)
    {
        $basePrice = 0;

        switch ($package) {
            case 1: // Basic
                $basePrice = $plan == 1 ? (35 + 20) : (357 + 20); // Monthly or Yearly
                break;
            case 2: // Deluxe
                $basePrice = $plan == 1 ? (35 + 1100) : (357 + 1100);
                break;
            case 3: // Premium
                $basePrice = $plan == 1 ? (35 + 1600) : (357 + 1600);
                break;
        }

        return $basePrice;
    }

    public function revenue()
    {
        $data['revenue'] = CompanyTransaction::with('company')->where('is_enable', 1)->get();

        return view('companies.revenue', $data);
    }

    public function renewal()
    {
        $data['userId'] = session('user_id');
        $data['userName'] = session('user_name');
        $data['companyId'] = session('company_id');
        $data['companyName'] = session('company_name');
        $data['package'] = session('package');
        $data['plan'] = session('plan');

        return view('companies.renewal', $data);
    }

    public function renewal_store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'user_id' => 'required|integer',
            'company_id' => 'required|integer',
            'package' => 'required|integer',
            'plan' => 'required|integer',
            'payment_method' => 'required|string', // Ensure payment method is required
        ]);

        // Set your Stripe secret key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Calculate the amount for the renewal
        $amount = $this->calculateAmount($request->package, $request->plan); // Adjust this method accordingly

        try {
            // Create the PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // Amount in cents
                'currency' => 'gbp',
                'payment_method' => $request->payment_method,
                'confirm' => true,
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
                'metadata' => [
                    'user_id' => $request->user_id,
                    'company_id' => $request->company_id,
                ],
            ]);

            // Return the PaymentIntent client secret to the client-side
            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
