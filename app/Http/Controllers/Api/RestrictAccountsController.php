<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\RestrictAccounts;
use Illuminate\Http\Request;
use Validator;

class RestrictAccountsController extends Controller
{
    // api for add restrict accounts
    function add_restrict_accounts(Request $request)
    {
        
        $validator = Validator::make($request->all(), [ 
            'login_id'   => 'required',
            'user_id'    => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $user_data= RestrictAccounts::where('login_id', $request->login_id)->where('user_id', $request->user_id)->first();
        if (empty($user_data)) 
        {   
            $restrictAccounts               = new RestrictAccounts();
            $restrictAccounts->login_id     = $request->login_id;
            $restrictAccounts->user_id      = $request->user_id;
            $restrictAccounts->content      = $request->content ? $request->content : '';
            $restrictAccounts->save(); 
            return response()->json(['msg'=>'Account restrict add successfully!.', 'status' =>'1']);
        }
        else
        { 
            return response()->json(['msg'=>'This account you have a already restrict applied.!', 'status' =>'0']);
        }
    }
}
