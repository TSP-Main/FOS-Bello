<?php
use App\Models\Company;

function is_software_manager()
{
    $user_role = Auth()->user()->role;
    return ($user_role  == config('constants.SOFTWARE_MANAGER')) ? true : false;
}

function view_permission($page_name = null)
{
    $user_role = auth()->user()->role;
    switch ($user_role) {

        case 1: // Software Manager
            switch ($page_name) {
                case 'dashboard':
                case 'users':
                case 'company':
                    return true;
                default:
                    return false;
            }

        case 2: // Company Super Admin
            switch ($page_name) {
                case 'dashboard':
                case 'users':
                case 'schedules':
                case 'products':
                case 'options':
                case 'categories':
                case 'menu':
                case 'orders':
                    return true;
                default:
                    return false;
            }

        case 3: // Company Shop Admin
            switch ($page_name) {
                case 'dashboard':
                case 'schedules':
                    return true;
                default:
                    return false;
            }

        case 4:
            switch ($page_name) {
                case 'home':
                case 'dashboard':
                    return true;
                default:
                    return false;
            }

        default:
            return false;
    }
}

function validate_token($token)
{
    // if (!$token) {
    //     return response()->json(['error' => 'Authorization token not found'], 401);
    // }

    $company = Company::where('token', $token)->where('is_enable', 1)->first();

    if ($company) {
        return response()->json(['status' => 'success', 'message' => 'success', 'company' => $company], 200);
    }
    else{
        return response()->json(['status' => 'error', 'message' => 'Unauthorized access'], 401);
    }
}