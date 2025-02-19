<?php


namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Videos;
use App\Models\VideoData;
use App\Models\User;
use App\Models\VideoComments;
use App\Models\VideoCommentPinned;
use Illuminate\Http\Request;
use Validator;

class VideoCommentPinnedController extends Controller
{
    // api for add video pinned
    public function add_comment_pinned(Request $request)
    {
        $user_id        = $request->user_id;
        $comment_id     = $request->comment_id;
        
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
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
            $pinned_data= VideoCommentPinned::where(['user_id' => $user_id,'comment_id' => $comment_id])->first();
            if (!empty($pinned_data)) 
            {
                return response()->json(['msg'=>'This comment already pinned', 'status' =>'0']);
            }
            else
            {
                $video_comment_data= VideoComments::where('id',$comment_id)->first();
                if (!empty($video_comment_data)) 
                {
                    $videocommentpinned              = new VideoCommentPinned();
                    $videocommentpinned->user_id     = $user_id;
                    $videocommentpinned->comment_id  = $comment_id;
                    $videocommentpinned->save(); 

                    return response()->json(['msg'=>'Comment pinned add successfully!.', 'status' =>'1']);
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

    // api for remove video pinned
    public function remove_comment_pinned(Request $request)
    {
        $user_id        = $request->user_id;
        $comment_id     = $request->comment_id;
        
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
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
            $comment_pinned_data= VideoCommentPinned::where(['user_id' => $user_id,'comment_id' => $comment_id])->first();
            if (!empty($comment_pinned_data)) 
            {
                VideoCommentPinned::where(['user_id' => $user_id,'comment_id' => $comment_id])->delete();
                return response()->json(['msg'=>'Comment pinned remove successfully', 'status' =>'1']);
            }
            else
            {
                return response()->json(['msg'=>'This comment not found pinned our database!.', 'status' =>'0']);
            }
        }
        else
        { 
            return response()->json(['msg'=>'This user not exist our database.!', 'status' =>'0']);
        }
    }
    
}
