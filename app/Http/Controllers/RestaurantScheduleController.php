<?php

namespace App\Http\Controllers;

use App\Models\RestaurantSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
