<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HelpCenter;
use App\Models\HelpCenterData;
use Illuminate\Http\Request;
use Validator;

class HelpCenterController extends Controller
{
   
    public function gethelp()
    {
        $helpCenter = HelpCenter::all();
        if(!empty($helpCenter)) {
            foreach ($helpCenter as $row) {
                $record[] = array(
                    "id"                    => $row->id,
                    "title"                 => $row->title,
                );
                $result = $record;
            }
            return response()->json(['data' => $result,'msg'=>'Help Center Data Get Successfully.', 'status' =>'1']);
        }
        return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
    }

    public function gethelpbyid(Request $request)
    {
        $id = $request->id;
        
        $validator = Validator::make($request->all(), [ 
            'id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $help_center = HelpCenter::where('id','=',$request->id)->first();
        $result_data['id']              = $help_center->id;
        $result_data['title']           = $help_center->title;
        return response()->json(['data' => $result_data,'msg'=>'Help Center Retrive Successfully.', 'status' =>'1']);
    }

    //api for problem resolved 
    function add_help_center_problem_resolved(Request $request)
    {
        $user_id            = $request->user_id;
        $help_center_id     = $request->help_center_id;
        $problem_resolved   = $request->problem_resolved; // Yes or No
         
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'help_center_id'   => 'required',
            'problem_resolved'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $chk_help_center = HelpCenter::where('id','=',$help_center_id)->first();
        if(!empty($chk_help_center)) 
        {    
            $Helpcenterdata                  = new HelpCenterData();
            $Helpcenterdata->user_id         = $user_id;
            $Helpcenterdata->help_center_id  = $help_center_id;
            $Helpcenterdata->problem_resolved= $problem_resolved;
            $Helpcenterdata->save(); 
            return response()->json(['msg'=>'Problem resolved add successfully.', 'status' =>'1']);
        } 
        else 
        {
            return response()->json(['msg'=>'This help center is not exist our database', 'status' =>'0']);
        }
    }

    
}
