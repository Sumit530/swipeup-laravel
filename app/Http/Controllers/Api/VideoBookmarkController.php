<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Videos;
use App\Models\VideoBookmark;
use App\Models\VideoWatchHistory;
use App\Models\VideoLikes;
use App\Models\VideoComments;
use Illuminate\Http\Request;
use Validator;
use Storage;
use File;

class VideoBookmarkController extends Controller
{
    // api for add video bookmark
    public function add_video_bookmark(Request $request)
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
            	$video_bookmark_data = VideoBookmark::where(['user_id' => $user_id,'video_id' => $video_id])->first();
		        if (empty($video_bookmark_data)) 
		        {
	                $videoBookmark               = new VideoBookmark();
	                $videoBookmark->user_id      = $user_id;
	                $videoBookmark->video_id     = $video_id;
	                $videoBookmark->save(); 

	                return response()->json(['msg'=>'Video bookmark add successfully', 'status' =>'1']);
	            }
	            else
	            {
	                return response()->json(['msg'=>'This video already added bookmark', 'status' =>'0']);
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

    // api for remove video bookmark
    public function remove_video_bookmark(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); die();
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'video_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
		$video_bookmark_data = VideoBookmark::where(['user_id' => $request->user_id,'video_id' => $request->video_id])->first();
        if (!empty($video_bookmark_data)) 
        {
            VideoBookmark::where(['user_id' => $request->user_id,'video_id' => $request->video_id])->delete();
       		return response()->json(['msg'=>'Video bookmark remove successfully', 'status' =>'1']);
       	}
       	else
       	{
       		return response()->json(['msg'=>'Video bookmark data not found..!', 'status' =>'0']);
       	}
    }
    
}
