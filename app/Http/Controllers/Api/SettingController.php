<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // api for terms of use
    function terms_of_use()
    {
         
        $setting_data= Setting::where('id',1)->first();
        if (!empty($setting_data)) 
        {   
            $result_data['details']     = $setting_data->terms_of_use;
            return response()->json(['data' => $result_data,'msg'=>'Terms of use get successfully.', 'status' =>'1']);
        }
        else
        { 
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        }
    }

    // api for privacy policy
    function privacy_policy()
    {
         
        $setting_data= Setting::where('id',1)->first();
        if (!empty($setting_data)) 
        {   
            $result_data['details']     = $setting_data->privacy_policy;
            return response()->json(['data' => $result_data,'msg'=>'Privacy policy get successfully.', 'status' =>'1']);
        }
        else
        { 
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        }
    }

    // api for copyright policy
    function copyright_policy()
    {
         
        $setting_data= Setting::where('id',1)->first();
        if (!empty($setting_data)) 
        {   
            $result_data['details']     = $setting_data->copyright_policy;
            return response()->json(['data' => $result_data,'msg'=>'Copyright policy get successfully.', 'status' =>'1']);
        }
        else
        { 
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        }
    }
}
