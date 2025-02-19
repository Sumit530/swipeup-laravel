<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\VideoCommentLikes;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Videos;
use App\Models\VideoData;
use Validator;

class VideoCommentLikesController extends Controller
{

    // api for add comment like
    public function add_comment_like(Request $request)
    {
        $user_id        = $request->user_id;
        $video_id       = $request->video_id;
        $comment_id     = $request->comment_id;
        
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'video_id'   => 'required',
            'comment_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $user_data= User::where('id', $user_id)->first();
        if (!empty($user_data)) 
        {   
            $like_data= VideoCommentLikes::where(['user_id' => $user_id,'video_id' => $video_id,'comment_id' => $comment_id])->first();
            if (!empty($like_data)) 
            {
                return response()->json(['msg'=>'This comment already likeed', 'status' =>'0']);
            }
            else
            {
                $video_data= Videos::where('id',$video_id)->first();
                if (!empty($video_data)) 
                {
                    $videolikes              = new VideoCommentLikes();
                    $videolikes->user_id     = $user_id;
                    $videolikes->video_id    = $video_id;
                    $videolikes->comment_id  = $comment_id;
                    $videolikes->save(); 

                    return response()->json(['msg'=>'Comment like successfully!.', 'status' =>'1']);
                }
                else
                {
                    return response()->json(['msg'=>'This comment not found our database!.', 'status' =>'0']);
                }
            }
        }
        else
        { 
            return response()->json(['msg'=>'This user not exist our database.!', 'status' =>'0']);
        }
    }

    // api for remove comment like
    public function remove_comment_like(Request $request)
    {
        $user_id        = $request->user_id;
        $video_id       = $request->video_id;
        $comment_id     = $request->comment_id;
        
        $validator = Validator::make($request->all(), [ 
            'user_id'    => 'required',
            'video_id'   => 'required',
            'comment_id' => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $user_data= User::where('id', $user_id)->first();
        if (!empty($user_data)) 
        {   
            $like_data= VideoCommentLikes::where(['user_id' => $user_id,'video_id' => $video_id,'comment_id' => $comment_id])->first();
            if (!empty($like_data)) 
            {
                VideoCommentLikes::where(['user_id' => $user_id,'video_id' => $video_id,'comment_id' => $comment_id])->delete();
                return response()->json(['msg'=>'Comment like remove successfully', 'status' =>'1']);
            }
            else
            {
                return response()->json(['msg'=>'This comment not found likes our database!.', 'status' =>'0']);
            }
        }
        else
        { 
            return response()->json(['msg'=>'This user not exist our database.!', 'status' =>'0']);
        }
    }
   
}
