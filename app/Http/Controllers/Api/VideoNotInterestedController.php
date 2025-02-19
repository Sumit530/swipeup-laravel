<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Videos;
use App\Models\VideoData;
use App\Models\VideoNotInterested;
use Illuminate\Http\Request;
use Validator;
use Storage;
use File;

class VideoNotInterestedController extends Controller
{
    // api for add video not interested
    public function add_video_not_interested(Request $request)
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
                $video_notint_data = VideoNotInterested::where(['user_id' => $user_id,'video_id' => $video_id])->first();
                if (empty($video_notint_data)) 
                {
                    $videonotinterested               = new VideoNotInterested();
                    $videonotinterested->user_id      = $user_id;
                    $videonotinterested->video_id     = $video_id;
                    $videonotinterested->save(); 

                    return response()->json(['msg'=>'Video not interested add successfully', 'status' =>'1']);
                }
                else
                {
                    return response()->json(['msg'=>'This video already added favorite', 'status' =>'0']);
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

    // api for remove video not interested
    public function remove_video_not_interested(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); die();
        $validator = Validator::make($request->all(), [ 
            'id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $video_notint_data = VideoNotInterested::where('id',$request->id)->first();
        if (!empty($video_notint_data)) 
        {
            VideoNotInterested::where('id',$request->id)->delete();
            return response()->json(['msg'=>'Video not interested remove successfully', 'status' =>'1']);
        }
        else
        {
            return response()->json(['msg'=>'Video favorite data not found..!', 'status' =>'0']);
        }
    }

    // api for video not interested list
    public function get_video_not_interested(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'user_id'  => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $video_notint_data = VideoNotInterested::where('user_id',$request->user_id)->get();
        if (count($video_notint_data) > 0) 
        {  
            foreach ($video_notint_data as $row) {
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
                    "id"                    => $row->id,
                    "video_id"              => $video_id,
                    "cover_image"           => $cover_image,
                    "video_url"             => $video_url,
                );
                $result = $record;
            }
            return response()->json(['data' => $result,'msg'=>'Video not interested get successfully.', 'status' =>'1']);
        }
        else
        {
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        } 
    }
}
