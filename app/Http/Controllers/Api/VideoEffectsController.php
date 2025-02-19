<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\VideoEffects;
use Illuminate\Http\Request;
use Validator;
use Storage;
use File;

class VideoEffectsController extends Controller
{
    
    // api for add effect
    public function add_effect(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name'      => 'required',
            'attachment'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        if ($request->file('attachment') != '') 
        {
            // song file
            $attachment = $request->file('attachment');
            $destinationPath =  Storage::disk('public')->path('uploads/effects');
            if(!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath,0777, true, true);
            }

            $attachment_fileName   = time()."_".rand(11111,99999).".".$attachment->getClientOriginalExtension();
            $upload_success = $attachment->move($destinationPath, $attachment_fileName);
        }
        else
        {
            $attachment_fileName = "";
        }

        $videoeffects                  = new VideoEffects();
        $videoeffects->name            = $request->name;
        $videoeffects->attachment      = isset($attachment_fileName) ? $attachment_fileName :'';
        $videoeffects->save();
        return response()->json(['msg'=>'Effect upload successfully!', 'status' =>'1']);
    }

    // api for list effect
    public function get_effect() {

        $effects_data  = VideoEffects::whereNull('deleted_at')->get();
        // echo "<pre>"; print_r($effects_data); die();
        if (count($effects_data) > 0) 
        {  
            foreach ($effects_data as $row) {
                // song file
                if ($row->attachment != '') 
                {
                    $destinationPath =  Storage::disk('public')->path('uploads/effects');
                    if(!empty($row->attachment)){
                        if(File::exists($destinationPath.'/'.$row->attachment)) {
                            $attachment = url('storage/app/public/uploads/effects/'.$row->attachment);
                        }
                        else
                        {
                            $attachment = "";
                        }
                    }
                    else
                    {
                        $attachment = "";
                    }
                }
                else
                {
                    $attachment = "";
                }

                $record[] = array(
                    "id"                    => $row->id,
                    "name"                  => $row->name,
                    "attachment"            => $attachment,
                );
                $result = $record;
            }
        }
        else
        {
            $result = array();
        }

        return response()->json(['data' => $result,'msg'=>'Effect list get successfully.', 'status' =>'1']);
    }
}
