<?php

namespace App\Http\Controllers;

use DateTimeZone;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\RestaurantEmail;
use App\Models\RestaurantSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Models\RestaurantStripeConfig;

class RestaurantScheduleController extends Controller
{
    public function index()
    {
        
    }

    public function create()
    {
        $data['schedules'] = RestaurantSchedule::where('company_id', Auth::user()->company_id)->get()->keyBy('day')->toArray();
        return view('schedules.create', $data);
    }

    public function store(Request $request)
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $companyId = Auth::user()->company_id;

        foreach ($days as $day) {
            $openingTime = $request->input(strtolower($day) . '_opening_time');
            $closingTime = $request->input(strtolower($day) . '_closing_time');
            $deliveryStartTime   = $request->input(strtolower($day) . '_delivery_start_time');
            $collectionStartTime = $request->input(strtolower($day) . '_collection_start_time');
            $isClosed = $request->has(strtolower($day) . '_is_closed');

            RestaurantSchedule::updateOrCreate(
                ['day' => $day, 'company_id' => $companyId],
                [
                    'company_id' => $companyId,
                    'opening_time' => $openingTime,
                    'closing_time' => $closingTime,
                    'delivery_start_time' => $deliveryStartTime,
                    'collection_start_time' => $collectionStartTime,
                    'is_closed' => $isClosed,
                    'created_by' => Auth::user()->id,
                ]
            );
        }

        return redirect()->back()->with('success', 'Schedule updated successfully.');

        // return redirect()->route('users.list')->with('success', 'User created successfully');
    }

    public function create_radius()
    {
        $company_id = Auth::user()->company_id;
        $response = Company::find($company_id);

        $data['address'] = $response->address;
        $data['city'] = $response->city;
        $data['postcode'] = $response->postcode;
        $data['radius'] = $response->radius;
        $data['latitude'] = $response->latitude;
        $data['longitude'] = $response->longitude;
        
        return view('radius.create2', $data);
    }

    public function store_radius(Request $request)
    {
        $request->validate([
            'address'   => 'required|string',
            'city'      => 'required|string',
            'postcode'  => 'required|string',
            'radius'    => 'required|numeric',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $company_id = Auth::user()->company_id;

        $data['address'] = $request->address;
        $data['city'] = $request->city;
        $data['postcode'] = $request->postcode;
        $data['radius'] = $request->radius;
        $data['latitude'] = $request->latitude;
        $data['longitude'] = $request->longitude;
        $data['updated_by'] = Auth::id();

        $company = Company::find($company_id);DB::enableQueryLog();
        $response = $company->update($data);

        return redirect()->route('radius.create')->with('success', 'Delivery Radius Addedd Successfully!');
    }

    public function create_timezone()
    {
        $company_id = Auth::user()->company_id;
        $response = Company::find($company_id);

        $data['timezone'] = $response->timezone;
        $data['timezonesList'] = DateTimeZone::listIdentifiers();
        
        return view('companies.timezone', $data);
    }

    public function store_timezone(Request $request)
    {
        $request->validate([
            'timezone' => 'required'
        ]);

        $company_id = Auth::user()->company_id;

        $data['timezone'] = $request->timezone;
        $data['updated_by'] = Auth::id();

        $company = Company::find($company_id);
        $response = $company->update($data);

        return redirect()->route('timezone.create')->with('success', 'Set Timezone Successfully!');
    }

    public function create_configurations()
    {
        $companyId = Auth::user()->company_id;
        $data['email'] = RestaurantEmail::where('company_id', $companyId)->first();
        $data['stripe'] = RestaurantStripeConfig::where('company_id', $companyId)->first();
        
        $restaurantDetail = Company::find($companyId);
        $data['amount'] = $restaurantDetail->free_shipping_amount;
        $data['currency'] = $restaurantDetail->currency;

        return view('companies.configurations', $data);
    }

    public function email_store(Request $request)
    {
        $request->validate([
            'mailer'    => 'required',
            'host'      => 'required',
            'port'      => 'required',
            'name'      => 'required',
            'username'  => 'required',
            'password'  => 'required'
        ]);

        $companyId = Auth::user()->company_id;

        $data['company_id'] = $companyId;
        $data['mailer']     = trim($request->mailer);
        $data['host']       = trim($request->host);
        $data['port']       = trim($request->port);
        $data['username']   = trim($request->username);
        $data['password']   = Crypt::encrypt(trim($request->password));
        $data['encryption'] = 'ssl';
        $data['address']    = trim($request->username);
        $data['name']       = trim($request->name);
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        $response = RestaurantEmail::updateOrCreate(
            ['company_id' => $companyId],
            $data
        );

        return redirect()->route('configurations.create')->with('success', 'Saved Successfully!');
    }

    public function stripe_store(Request $request)
    {
        $request->validate([
            'stripe_key'    => 'required',
            'stripe_secret' => 'required',
        ]);

        $companyId = Auth::user()->company_id;

        $data['company_id'] = $companyId;
        $data['stripe_key'] = Crypt::encrypt(trim($request->stripe_key));
        $data['stripe_secret'] = Crypt::encrypt(trim($request->stripe_secret));
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        $response = RestaurantStripeConfig::updateOrCreate(
            ['company_id' => $companyId],
            $data
        );

        return redirect()->route('configurations.create')->with('success', 'Saved Successfully!');
    }

    public function free_shipping_store(Request $request)
    {
        $request->validate([
            'amount'    => 'required',
        ]);

        $companyId = Auth::user()->company_id;

        $data['free_shipping_amount'] = $request->amount;
        $data['updated_by'] = Auth::id();

        $company = Company::find($companyId);
        $response = $company->update($data);

        return redirect()->route('configurations.create')->with('success', 'Saved Successfully!');
    }

    public function currency_store(Request $request)
    {
        $request->validate([
            'currency' => 'required',
        ]);

        $companyId = Auth::user()->company_id;
        $data['currency'] = $request->currency;
        $data['currency_symbol'] = currency_list()[$request->currency]['symbol'];
        $data['updated_by'] = Auth::id();

        $company = Company::find($companyId);
        $response = $company->update($data);

        return redirect()->route('configurations.create')->with('success', 'Saved Successfully!');
    }
}
