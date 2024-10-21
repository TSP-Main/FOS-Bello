<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NewsletterSubscription;

class NewsletterSubscriptionController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;
        $data['subscriptions'] = NewsletterSubscription::where('company_id', $companyId)->get();
        
        return view('newsletter.list', $data);
    }
}
