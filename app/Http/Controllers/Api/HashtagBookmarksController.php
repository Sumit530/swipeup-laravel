<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Videos;
use App\Models\Hashtag;
use App\Models\HashtagBookmarks;
use Illuminate\Http\Request;
use Validator;

class HashtagBookmarksController extends Controller
{
    // api for add hashtag bookmark
    public function add_hashtag_bookmark(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); die();
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'hashtag_id'  => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $user_data= User::where('id', $request->user_id)->first();
        if (!empty($user_data)) 
        {   
            $hashtag_data = hashtag::where('id',$request->hashtag_id)->first();
            if (!empty($hashtag_data)) 
            {
                $sound_bookmark_data = HashtagBookmarks::where(['user_id' => $request->user_id,'hashtag_id' => $request->hashtag_id])->first();
                if (empty($sound_bookmark_data)) 
                {
                    $hashtagbookmarks             = new HashtagBookmarks();
                    $hashtagbookmarks->user_id    = $request->user_id;
                    $hashtagbookmarks->hashtag_id = $request->hashtag_id;
                    $hashtagbookmarks->save(); 

                    return response()->json(['msg'=>'hashtag bookmark add successfully', 'status' =>'1']);
                }
                else
                {
                    return response()->json(['msg'=>'This hashtag already added bookmark', 'status' =>'0']);
                }
            }
            else
            {
                return response()->json(['msg'=>'This hashtag not found..!.', 'status' =>'0']);
            }
        }
        else
        { 
            return response()->json(['msg'=>'This user not exist our database.!', 'status' =>'0']);
        }
    }

    // api for remove hashtag bookmark
    public function remove_hashtag_bookmark(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); die();
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'hashtag_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $sound_bookmark_data = HashtagBookmarks::where(['user_id' => $request->user_id,'hashtag_id' => $request->hashtag_id])->first();
        if (!empty($sound_bookmark_data)) 
        {
            HashtagBookmarks::where(['user_id' => $request->user_id,'hashtag_id' => $request->hashtag_id])->delete();
            return response()->json(['msg'=>'Hashtag bookmark remove successfully', 'status' =>'1']);
        }
        else
        {
            return response()->json(['msg'=>'Hashtag bookmark data not found..!', 'status' =>'0']);
        }
    }

}
