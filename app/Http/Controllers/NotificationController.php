<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        // Get the company_id from the currently authenticated user
        $companyId = Auth::user()->company_id;
        $UserId = Auth::user()->id;

        // Fetch notifications related to the company
        $notifications = DB::table('notifications')
            ->whereRaw('JSON_EXTRACT(data, "$.company_id") = ?', [$companyId])
            ->where('notifiable_id', $UserId)
            ->get();

        return response()->json($notifications);
    }

    public function clear(Request $request)
    {
        // Get the company_id from the currently authenticated user
        $companyId = Auth::user()->company_id;

        // Fetch notifications related to the company and delete them
        DB::table('notifications')
            ->whereRaw('JSON_EXTRACT(data, "$.company_id") = ?', [$companyId])
            ->delete();

        return response()->json(['status' => 'success']);
    }

        public function delete($id)
        {
            // Get the company_id from the currently authenticated user
            $companyId = Auth::user()->company_id;

            // Find and delete the specific notification if it belongs to the company
            $notification = Notification::where('id', $id)
                ->whereRaw('JSON_EXTRACT(data, "$.company_id") = ?', [$companyId])
                ->firstOrFail();

            $notification->delete();

            return response()->json(['status' => 'success']);
        }

    }
