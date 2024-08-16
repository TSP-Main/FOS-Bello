<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;

class UserController extends Controller
{
    protected $constants;

    public function __construct()
    {
        $this->constants = config('constants');
    }

    public function index()
    {
        $user = Auth::user();
        if(is_software_manager()){
            $data['users'] = User::with('company')->where('is_enable', 1)->where('id', '!=', $user->id)->get();
        }
        else{
            $data['users'] = User::where('is_enable', 1)->where('company_id', $user->company_id)->where('id', '!=', $user->id)->get();
        }
        
        return view('users.list', $data);
    }

    public function create()
    {
        $data['roles'] = $this->constants['USER_ROLES_NAME'];

        if(Auth::user()->role == $this->constants['SOFTWARE_MANAGER']){
            $data['companies'] = Company::where('is_enable', 1)->get();
        }
        else{
            $data['companies'] = Company::where('id', Auth::user()->company_id)->where('is_enable', 1)->get();
            unset($data['roles'][2]);
        }
        unset($data['roles'][1]);
        return view('users.create', $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required',
            'email'     => 'required|email',
            'password'  => 'required',
            'company'   => 'required',
            'role'      => 'required',
        ]);

        $user = new User();
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->password     = Hash::make($request->password);
        $user->company_id   = $request->company;
        $user->role         = $request->role;
        $user->created_by   = Auth::id();

        $response = $user->save();

        return redirect()->route('users.list')->with('success', 'User created successfully');
    }

    public function edit($id)
    {
        $data['user'] = User::with('company:id,name')->find($id);
        $data['roles'] = $this->constants['USER_ROLES_NAME'];

        if(Auth::user()->role == $this->constants['SOFTWARE_MANAGER']){
            $data['companies'] = Company::where('is_enable', 1)->get();
        }
        else{
            $data['companies'] = Company::where('id', Auth::user()->company_id)->where('is_enable', 1)->get();
            unset($data['roles'][2]);
        }
        unset($data['roles'][1]);

        return view('users.edit', $data);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required',
            'email'     => 'required|email',
            'company'   => 'required',
            'role'      => 'required',
        ]);

        $post_data['name']        = $request->name;
        $post_data['email']       = $request->email;
        $post_data['company_id']  = $request->company;
        $post_data['role']        = $request->role;
        $post_data['updated_by']  = Auth::id();

        if (!empty($request->password)) {
            $post_data['password'] = Hash::make($request->password);
        }

        $user = User::find($request->id);
        $response = $user->update($post_data);

        return redirect()->route('users.list')->with('success', 'User updated successfully');
    }
}
