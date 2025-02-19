<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Videos;
use App\Models\VideoData;
use App\Models\VideoWatchHistory;
use Illuminate\Http\Request;
use Validator;
use Storage;
use File;

class VideoWatchHistoryController extends Controller
{
    // api for add video watch history
    public function add_watch_video_history(Request $request)
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
            $video_watch_data = VideoWatchHistory::where(['user_id' => $user_id,'video_id' => $video_id])->first();
            if (empty($video_watch_data)) 
            {
                $Videowatchhistory               = new VideoWatchHistory();
                $Videowatchhistory->user_id      = $user_id;
                $Videowatchhistory->video_id     = $video_id;
                $Videowatchhistory->save(); 

                return response()->json(['msg'=>'Video watch history add successfully', 'status' =>'1']);
            }
            else
            {
                return response()->json(['msg'=>'This video already watch..!.', 'status' =>'0']);
            }
        }
        else
        { 
            return response()->json(['msg'=>'This user not exist our database.!', 'status' =>'0']);
        }
    }

    // api for video watch list
    public function get_watch_video_history(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'video_id'  => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $video_watch_data = VideoWatchHistory::where('video_id',$request->video_id)->get();
        if (count($video_watch_data) > 0) 
        {  
            foreach ($video_watch_data as $row) {
                $user_details    = User::where('id',$row->user_id)->first();
                if ($user_details != '') 
                {
                    $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                    if(!empty($user_details->profile_image)){
                        if(File::exists($destinationPath.'/'.$user_details->profile_image)) {
                            $profile_image = url('storage/app/public/uploads/user/profile/'.$user_details->profile_image);
                        }
                        else
                        {
                            $profile_image = "";
                        }
                    }
                    else
                    {
                        $profile_image = "";
                    }

                    $username = $user_details->name;
                    $user_username = $user_details->username;
                }
                else
                {
                    $username = "";
                    $user_username = "";
                    $profile_image = "";
                }

                $record[] = array(
                    "id"                    => $row->id,
                    "name"                  => $username,
                    "username"              => $user_username,
                    "profile_image"         => $profile_image,
                );
                $result = $record;
            }
            return response()->json(['data' => $result,'msg'=>'Video watch list get successfully.', 'status' =>'1']);
        }
        else
        {
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        } 
    }
}
