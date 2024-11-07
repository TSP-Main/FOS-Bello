<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Stripe\Stripe;
use App\Models\Company;
use Stripe\PaymentIntent;
use App\Models\ApiTokenLog;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CompanyTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
            // $data['is_enable'] = $request->status;
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

    public function generate_new_token(Request $request)
    {
        $companyId = base64_decode($request->company_id);
        $company = Company::findOrFail($companyId);
    
        // Generate new token and save to the company
        $newToken = 'tspkeyusmkeyanikey_apikeypunkeychar' . Str::random(60) . $companyId;
        $company->token = $newToken;
        $company->save();
    
        // Log the token generation
        ApiTokenLog::create([
            'company_id' => $companyId,
            'reason' => $request->reason,
            'new_token' => $newToken,
        ]);
    
        return response()->json(['success' => true, 'message' => 'Token generated successfully']);
    }

    // Register restaurant by self on landing page
    public function register(Request $request)
    {
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

        // send email when new user register
        if($response){
            $toEmail = 'admin@bellofos.com';

            $subject = 'Bello FOS';
            $message = 'New user want to buy subscription in Bello. Kindly login in Bello to view.';

            Mail::raw($message, function ($mail) use ($toEmail, $subject) {
                $mail->to($toEmail)
                    ->subject($subject);
            });
        }

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

                    if($paymentIntent->status == 'succeeded'){
                        // Add Company transation data
                        CompanyTransaction::create([
                            'company_id'    => $company->id,
                            'package'       => $company->package,
                            'plan'          => $company->plan,
                            'amount'        => $amount,
                            'status'        => 'New',
                            'stripe_payment_intent_id' => $paymentIntent->id,
                        ]);

                        $token = 'tspkeyusmkeyanikey_apikeypunkeychar' . Str::random(60) . $company->id;
                        $company->token = $token;
                        $company->status = config('constants.ACTIVE_RESTAURANT');
                        $company->accepted_date = Carbon::now();

                        if ($company->plan == 1) {
                            $company->expiry_date = $company->accepted_date->copy()->addMonth();
                        } elseif ($company->plan == 2) {
                            $company->expiry_date = $company->accepted_date->copy()->addYear();
                        }

                        $route      = 'companies.list';
                        $msg        = 'New Restaurant Added.';
                        $msgStatus  = 'success';
                    }
                } catch (\Exception $e) {
                    return redirect()->route('companies.incoming.list')->with('error', $e->getMessage());
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
            ->update(['status' => config('constants.IN_ACTIVE_RESTAURANT')]);

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
        $data['revenue'] = CompanyTransaction::with('company')->get();
        return view('companies.revenue', $data);
    }

    public function renewal()
    {
        if(Auth::user() && !session('user_id') && !session('company_id')){
            $user = Auth::user();
            $company = Company::find($user->company_id);

            $data['userId'] = $user->id;
            $data['userName'] = $user->name;
            $data['companyId'] = $company->id;
            $data['companyName'] = $company->name;
            $data['package'] = $company->package;
            $data['plan'] = $company->plan;
        }
        else{
            $data['userId'] = session('user_id');
            $data['userName'] = session('user_name');
            $data['companyId'] = session('company_id');
            $data['companyName'] = session('company_name');
            $data['package'] = session('package');
            $data['plan'] = session('plan');
        }

        return view('companies.renewal', $data);
    }

    public function renewal_store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'company_id' => 'required|integer',
            'package' => 'required|integer',
            'plan' => 'required|integer',
            'payment_method' => 'required|string',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $amount = $this->calculateAmount($request->package, $request->plan);

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

            if($paymentIntent->status == 'succeeded'){
                // Add Company transaction data
                CompanyTransaction::create([
                    'company_id' => $request->company_id,
                    'package' => $request->package,
                    'plan' => $request->plan,
                    'amount' => $amount,
                    'status' => 'Renew',
                    'stripe_payment_intent_id' => $paymentIntent->id,
                ]);

                $company = Company::find($request->company_id);
                $company->status = config('constants.ACTIVE_RESTAURANT');

                if ($request->plan == 1) {
                    $company->expiry_date = Carbon::now()->copy()->addMonth();
                } elseif ($request->plan == 2) {
                    $company->expiry_date = Carbon::now()->copy()->addYear();
                }
                $company->update();
            }

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

    public function api_logs()
    {
        $data['logs'] = ApiTokenLog::with('company')->get();
        return view('companies.api_logs', $data);
    }

    public function view($id)
    {
        $data['company'] = Company::with(['transactions', 'apiTokenLogs'])->find(base64_decode($id));

        return view('companies.view', $data);
    }
}
