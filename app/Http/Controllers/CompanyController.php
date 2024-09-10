<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index()
    {
        $data['companies'] = Company::where('status', 1)->get();
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
            'subscription_date' => 'required',
            'status' => 'required|in:1,2',
        ]);

        // Generate a unique token
        $token = 'tspkeyusmkeyanikey_apikeypunkeychar' . Str::random(60);
        $company             = new Company();
        $company->name       = $request->name;
        $company->email      = $request->email;
        $company->address    = $request->address;
        $company->subscription_date = $request->subscription_date;
        $company->status     = $request->status;
        $company->token      = $token;
        $company->created_by = Auth::user()->id;
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
            'subscription_date' => 'required',
            'status' => 'required'
        ]);

        if ($request->id) {
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            // $data['address'] = $request->address;
            $data['subscription_date'] = $request->subscription_date;
            $data['status'] = $request->status;
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

    public function incoming_request()
    {
        $data['requests'] = Company::where('status', 2)->get();
        return view('companies.incoming_request', $data);
    }

    public function incoming_request_action($id)
    {
        $id = base64_decode($id);

        $company = Company::find($id);
        $company['status'] = 1;
        $company['updated_by'] = Auth::user()->id;
        $response = $company->update();
        
        return redirect()->route('companies.list')->with('success', 'New Restaurant Added');
    }
}
