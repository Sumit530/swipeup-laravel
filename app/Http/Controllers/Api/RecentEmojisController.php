<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RecentEmojis;
use Validator;
class RecentEmojisController extends Controller
{
    // api for add  recent emoji
    public function add_recent_emoji(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'emoji'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $recentEmojis                   = new RecentEmojis();
        $recentEmojis->user_id          = $request->user_id;
        $recentEmojis->emoji            = $request->emoji;
        $recentEmojis->save();

        return response()->json(['msg'=>'Recent emoji add successfully!', 'status' =>'1']);
    }

    // api for recent emoji
    public function get_recent_emoji(Request $request) {

        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $recent_emoji_data = RecentEmojis::where('user_id','=',$request->user_id)->orderBy('created_at','DESC')->get();
        if (count($recent_emoji_data) > 0) 
        {  
            foreach ($recent_emoji_data as $row) {
                $record[] = array(
                    "id"                    => $row->id,
                    "emoji"                 => $row->emoji,
                );
                $result = $record;
            }
            return response()->json(['data' => $result,'msg'=>'Recent emoji get successfully.', 'status' =>'1']);
        }
        else
        {
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        } 
    }
}
