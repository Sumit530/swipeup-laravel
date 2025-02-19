<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Interests;
use Illuminate\Http\Request;
use Validator;
class InterestsController extends Controller
{
     // api for get interests list
    function interests_list()
    {
        $interests_data = Interests::whereNull('deleted_at')->get();
        if (count($interests_data) > 0) {
            return response()->json(['data' => $interests_data, 'msg'=>'interests get successfully.', 'status' =>'1']);
        }
        return response()->json(['msg'=>'language details not found.', 'status' =>'0']);
    }
}
