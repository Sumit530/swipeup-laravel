<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Videos;
use App\Models\VideoData;
use App\Models\VideoDuets;
use Illuminate\Http\Request;
use Validator;
use Storage;
use File;

class VideoDuetsController extends Controller
{
    // api for add video duets
    public function add_video_duet(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); die();
        $user_id        = $request->user_id;
        $video_id       = $request->video_id;

        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'video_id'  => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $user_data= User::where('id', $user_id)->first();
        if (!empty($user_data)) 
        {   
            $video_data = Videos::where('id',$video_id)->first();
            if (!empty($video_data)) 
            {
                $video_duets_data = VideoDuets::where(['user_id' => $user_id,'video_id' => $video_id])->first();
                if (empty($video_duets_data)) 
                {
                    $videoduets               = new VideoDuets();
                    $videoduets->user_id      = $user_id;
                    $videoduets->video_id     = $video_id;
                    $videoduets->save(); 

                    return response()->json(['msg'=>'Video duet add successfully', 'status' =>'1']);
                }
                else
                {
                    return response()->json(['msg'=>'This video already added duet', 'status' =>'0']);
                }
            }
            else
            {
                return response()->json(['msg'=>'This video not found..!.', 'status' =>'0']);
            }
        }
        else
        { 
            return response()->json(['msg'=>'This user not exist our database.!', 'status' =>'0']);
        }
    }

    
    // api for video duets list
    public function get_video_duets(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'user_id'  => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $video_duets_data = VideoDuets::where('user_id',$request->user_id)->get();
        if (count($video_duets_data) > 0) 
        {  
            foreach ($video_duets_data as $row) {
                $video_details    = Videos::where('id',$row->video_id)->first();
                if ($video_details != '') 
                {
                    if ($video_details->cover_image != '') 
                    {
                        $deldestinationPath =  Storage::disk('public')->path('uploads/videos/cover_images');
                        if(File::exists($deldestinationPath.'/'.$video_details->cover_image)) {
                            $cover_image = url('storage/app/public/uploads/videos/cover_images/'.$video_details->cover_image);
                        }
                        else
                        {
                            $cover_image = "";
                        }
                    }
                    else
                    {
                        $cover_image = "";
                    }
                    // delete video
                    if ($video_details->file_name != '') 
                    {
                       $delddestinationPath =  Storage::disk('public')->path('uploads/videos/videos');
                        if(File::exists($delddestinationPath.'/'.$video_details->file_name)) {
                            $video_url = url('storage/app/public/uploads/videos/videos/'.$video_details->file_name);
                        }
                        else
                        {
                            $video_url = "";
                        }
                    }
                    else
                    {
                        $video_url = "";
                    }
                    
                    $video_id = $video_details->id;
                }
                else
                {
                    $video_id = "";
                    $cover_image = "";
                    $video_url = "";
                }
                $record[] = array(
                    "duet_id"               => $row->id,
                    "video_id"              => $video_id,
                    "cover_image"           => $cover_image,
                    "video_url"             => $video_url,
                );
                $result = $record;
            }
            return response()->json(['data' => $result,'msg'=>'Duet video get successfully.', 'status' =>'1']);
        }
        else
        {
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        } 
    }
}
