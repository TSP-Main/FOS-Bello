<?php

namespace App\Http\Controllers;

use DateTimeZone;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\RestaurantSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
}
