<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\Videos;
use App\Models\Setting;
use App\Models\Songs;
use App\Models\Categories;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Hash;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $last_users = User::orderBy('id', 'desc')->take(7)->get();
        $total_active_users = User::where('status', '1')->count();
        $total_block_users = User::where('status', '0')->count();
        $total_users = User::whereNull('deleted_at')->count();
        $total_videos = Videos::whereNull('deleted_at')->count();
        $total_songs = Songs::whereNull('deleted_at')->count();
        $total_categories = Categories::whereNull('deleted_at')->count();

        $date = Carbon::now()->subDays(7);
        $past_seven_days_users = User::where('created_at', '>=', $date)->count();
        return view('dashboard',compact('last_users','total_users','total_videos','total_songs','total_categories','past_seven_days_users','total_active_users','total_block_users'));
    }

    public function profile_details()
    {
        // $user_data = Auth::user();
        // echo "<pre>"; print_r($user_data->toArray()); die();
        return view('profile');
    }
    public function setting_details()
    {
        $setting_details = Setting::where('id','1')->first();
        return view('setting',compact('setting_details'));
    }

    public function profile_update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        if ($request->password != '' && $request->new_password != '') 
        {
            if (Hash::check($request->password,Auth::user()->password)) { 
                $data_update['password'] = Hash::make($request->new_password);
            } 
            else 
            {
                return redirect()->route('admin.profile')->with('error', 'Password does not match..!');
            }
        }
        $user_id = Auth::user()->id;
        $data_update['name'] = isset($request->name) ? $request->name : Auth::user()->name;
        Admin::where('id',$user_id)->update($data_update);
        
        return redirect()->route('admin.profile')->with('success', 'Profile detail update successfully.');
    }

    public function setting_update(Request $request)
    {
        
        $setting_details = Setting::where('id','1')->first();
        $data_update['terms_of_use'] = isset($request->terms_of_use) ? $request->terms_of_use : $setting_details->terms_of_use;
        $data_update['privacy_policy'] = isset($request->privacy_policy) ? $request->privacy_policy : $setting_details->privacy_policy;
        $data_update['copyright_policy'] = isset($request->copyright_policy) ? $request->copyright_policy : $setting_details->copyright_policy;
        Setting::where('id',1)->update($data_update);
        
        return redirect()->route('admin.settings')->with('success', 'Setting details update successfully.');
    }
}
