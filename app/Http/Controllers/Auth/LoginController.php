<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Admin;
use Validator;
use Hash;
use Auth;
use DB;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function guard()
    {
        // return Auth::guard('guest');
    }

    public function dologin(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'email'   => 'required|email|unique:admin,email',
            'password'   => 'required',
        ]);
        $user_data = Admin::where('email', $request->email)->first();
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if(Auth::attempt($credentials)) {
            return redirect()->route('admin.dashboard');       
        }
        // echo "<pre>"; print_r($user_data); die();
        if ($user_data != '') 
        {
            if (!Hash::check($request->password, $user_data->password)) {
                return \Redirect::back()->with('error', 'Password not match');
            }
            else
            {
                Auth::login($user_data);
                // return \Redirect::route('dashboard');
                return redirect()->route('admin.dashboard')->with('success', 'New country added successfully.');
            
            }
        }
        else
        {   
            // echo 1; die();
            return \Redirect::back()->with('error', 'Please enter valid login details.!');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        // \Session::flush();
        return redirect('/admin');
    }
    
    public function db()
    {
         dd(DB::connection() );
    } 
    
    

}
