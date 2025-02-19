<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\AccountVerification;
use Illuminate\Http\Request;
use Storage;
use File;
use Validator;

class AccountVerificationController extends Controller
{
     //api for Account Verification 
    function add_account_verification(Request $request)
    {
         
        $validator = Validator::make($request->all(), [ 
            'user_id'       => 'required',
            'full_name'     => 'required',
            'document_type' => 'required',
            'document'      => 'required',
            'category_id'   => 'required',
            'country_id'    => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['msg'=>$message, 'status' =>'0']);            
        }

        $chk_user = AccountVerification::where('user_id','=',$request->user_id)->first();
        if(empty($chk_user)) 
        {    
            if ($request->file('document') != '') 
            {
                //cover image
                $file_cover_image = $request->file('document');
                $destinationPathcover_image =  Storage::disk('public')->path('uploads/account/verification');
                if(!File::exists($destinationPathcover_image)) {
                    File::makeDirectory($destinationPathcover_image,0777, true, true);
                }
                $document   = time()."_".rand(11111,99999).".".$file_cover_image->getClientOriginalExtension();
                $file_cover_image->move($destinationPathcover_image, $document);
            }
            else
            {
                $document = "";
            }
            $accountverification                  = new AccountVerification();
            $accountverification->user_id         = $request->user_id;
            $accountverification->full_name       = $request->full_name;
            $accountverification->document_type   = $request->document_type;
            $accountverification->document        = $document ? $document : '';
            $accountverification->url1            = $request->url1 ? $request->url1 : '';
            $accountverification->link_type3      = $request->link_type3 ? $request->link_type3 : '';
            $accountverification->url3            = $request->url3 ? $request->url3 : '';
            $accountverification->link_type4      = $request->link_type4 ? $request->link_type4 : '';
            $accountverification->url4            = $request->url4 ? $request->url4 : '';
            $accountverification->link_type5      = $request->link_type5 ? $request->link_type5 : '';
            $accountverification->url5            = $request->url5 ? $request->url5 : '';
            $accountverification->save(); 

            return response()->json(['msg'=>'New request for verification add successfully.', 'status' =>'1']);
        } 
        else 
        {
            return response()->json(['msg'=>'Already send request for verification', 'status' =>'0']);
        }
    }
}
