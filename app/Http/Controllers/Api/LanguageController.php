<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\User;
use Validator;
use Hash;

class LanguageController extends Controller
{
    public $successStatus = 200;
    public $errorStatus = 300;

    public function Index() {
    	$languages = Language::query()->whereNull('deleted_at')->get();
    	if(!empty($languages)) {
			return response()->json(['data' => $languages,'msg'=>'Language List Retrive Successfully.', 'status' =>'1']);
    	}

    	return response()->json(['data' => [],'msg'=>'No Data Found', 'status' =>'1']);
    }

    // api for get language
    function get_user_language(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        $user_language_data = User::leftJoin('languages','languages.id','=','users.language_id')
                                ->select('users.language_id as language_id','languages.language_name as language_name')
                                ->where('users.id',$request->user_id)
                                ->first();
        if ($user_language_data != '') {
            return response()->json(['data' => $user_language_data, 'msg'=>'language details get successfully.', 'status' =>'1']);
        }
        return response()->json(['msg'=>'language details not found.', 'status' =>'0']);
    }

  
}
