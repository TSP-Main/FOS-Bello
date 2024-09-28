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
                case 'setup':
                case 'products':
                case 'options':
                case 'categories':
                case 'menu':
                case 'orders':
                case 'newsletter':
                    return true;
                default:
                    return false;
            }

        case 3: // Company Shop Admin
            switch ($page_name) {
                case 'dashboard':
                case 'setup':
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

function currency_list()
{
    return [
        'USD' => ['name' => 'United States Dollar', 'symbol' => '$'],
        'EUR' => ['name' => 'Euro', 'symbol' => '€'],
        'GBP' => ['name' => 'British Pound Sterling', 'symbol' => '£'],
        'JPY' => ['name' => 'Japanese Yen', 'symbol' => '¥'],
        'AUD' => ['name' => 'Australian Dollar', 'symbol' => 'A$'],
        'CAD' => ['name' => 'Canadian Dollar', 'symbol' => 'C$'],
        'CHF' => ['name' => 'Swiss Franc', 'symbol' => 'CHF'],
        'CNY' => ['name' => 'Chinese Yuan', 'symbol' => '¥'],
        'INR' => ['name' => 'Indian Rupee', 'symbol' => '₹'],
        'RUB' => ['name' => 'Russian Ruble', 'symbol' => '₽'],
        'BRL' => ['name' => 'Brazilian Real', 'symbol' => 'R$'],
        'ZAR' => ['name' => 'South African Rand', 'symbol' => 'R'],
        'NZD' => ['name' => 'New Zealand Dollar', 'symbol' => 'NZ$'],
        'SGD' => ['name' => 'Singapore Dollar', 'symbol' => 'S$'],
        'HKD' => ['name' => 'Hong Kong Dollar', 'symbol' => 'HK$'],
        'SEK' => ['name' => 'Swedish Krona', 'symbol' => 'kr'],
        'NOK' => ['name' => 'Norwegian Krone', 'symbol' => 'kr'],
        'MXN' => ['name' => 'Mexican Peso', 'symbol' => 'MX$'],
        'DKK' => ['name' => 'Danish Krone', 'symbol' => 'kr'],
        'MYR' => ['name' => 'Malaysian Ringgit', 'symbol' => 'RM'],
        'THB' => ['name' => 'Thai Baht', 'symbol' => '฿'],
        'IDR' => ['name' => 'Indonesian Rupiah', 'symbol' => 'Rp'],
        'PHP' => ['name' => 'Philippine Peso', 'symbol' => '₱'],
        'PLN' => ['name' => 'Polish Zloty', 'symbol' => 'zł'],
        'ISK' => ['name' => 'Icelandic Krona', 'symbol' => 'kr'],
        'CZK' => ['name' => 'Czech Koruna', 'symbol' => 'Kč'],
        'HUF' => ['name' => 'Hungarian Forint', 'symbol' => 'Ft'],
        'AED' => ['name' => 'United Arab Emirates Dirham', 'symbol' => 'د.إ'],
        'SAR' => ['name' => 'Saudi Riyal', 'symbol' => 'ر.س'],
        'TRY' => ['name' => 'Turkish Lira', 'symbol' => '₺'],
        'ILS' => ['name' => 'Israeli New Shekel', 'symbol' => '₪'],
        'ARS' => ['name' => 'Argentine Peso', 'symbol' => '$'],
        'CLP' => ['name' => 'Chilean Peso', 'symbol' => '$'],
        'COP' => ['name' => 'Colombian Peso', 'symbol' => '$'],
        'PEN' => ['name' => 'Peruvian Nuevo Sol', 'symbol' => 'S/'],
        'PKR' => ['name' => 'Pakistani Rupee', 'symbol' => '₨'],
        'NGN' => ['name' => 'Nigerian Naira', 'symbol' => '₦'],
        'EGP' => ['name' => 'Egyptian Pound', 'symbol' => '£'],
        'KWD' => ['name' => 'Kuwaiti Dinar', 'symbol' => 'د.ك'],
        'QAR' => ['name' => 'Qatari Rial', 'symbol' => 'ر.ق'],
        'OMR' => ['name' => 'Omani Rial', 'symbol' => 'ر.ع.'],
        'JOD' => ['name' => 'Jordanian Dinar', 'symbol' => 'د.ا'],
    ];
}