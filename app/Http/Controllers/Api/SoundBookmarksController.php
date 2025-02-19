<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\SoundBookmarks;
use App\Models\Songs;
use App\Models\Singers;
use App\Models\User;
use App\Models\Categories;
use App\Models\Videos;
use Illuminate\Http\Request;
use Validator;
use Storage;
use File;

class SoundBookmarksController extends Controller
{
    // api for add sound bookmark
    public function add_sound_bookmark(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); die();
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'sound_id'  => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $user_data= User::where('id', $request->user_id)->first();
        if (!empty($user_data)) 
        {   
            $songs_data = Songs::where('id',$request->sound_id)->first();
            if (!empty($songs_data)) 
            {
                $sound_bookmark_data = SoundBookmarks::where(['user_id' => $request->user_id,'sound_id' => $request->sound_id])->first();
                if (empty($sound_bookmark_data)) 
                {
                    $soundbookmarks               = new SoundBookmarks();
                    $soundbookmarks->user_id      = $request->user_id;
                    $soundbookmarks->sound_id     = $request->sound_id;
                    $soundbookmarks->save(); 

                    return response()->json(['msg'=>'Song bookmark add successfully', 'status' =>'1']);
                }
                else
                {
                    return response()->json(['msg'=>'This song already added bookmark', 'status' =>'0']);
                }
            }
            else
            {
                return response()->json(['msg'=>'This song not found..!.', 'status' =>'0']);
            }
        }
        else
        { 
            return response()->json(['msg'=>'This user not exist our database.!', 'status' =>'0']);
        }
    }

    // api for remove Song bookmark
    public function remove_song_bookmark(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); die();
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'sound_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $sound_bookmark_data = SoundBookmarks::where(['user_id' => $request->user_id,'sound_id' => $request->sound_id])->first();
        if (!empty($sound_bookmark_data)) 
        {
            SoundBookmarks::where(['user_id' => $request->user_id,'sound_id' => $request->sound_id])->delete();
            return response()->json(['msg'=>'Song bookmark remove successfully', 'status' =>'1']);
        }
        else
        {
            return response()->json(['msg'=>'Song bookmark data not found..!', 'status' =>'0']);
        }
    }

    // api for Song bookmark list
    public function get_song_bookmarks(Request $request) {

        $result  = array();
        $validator = Validator::make($request->all(), [ 
            'user_id'  => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $song_bookmark_data = SoundBookmarks::leftJoin('songs','songs.id','=','sound_bookmarks.sound_id')
                                ->select("sound_bookmarks.*","songs.*","songs.id as song_id","sound_bookmarks.id as id")
                                ->where('sound_bookmarks.user_id','=',$request->user_id)
                                ->orderBy('sound_bookmarks.id','DESC')
                                ->get();
        // echo "<pre>"; print_r($song_bookmark_data->toArray()); die();
        if (count($song_bookmark_data) > 0) 
        {  
            foreach ($song_bookmark_data as $val) {
                $total_videos = Videos::where('song_id','=',$val->song_id)->count();
                if ($val->attachment != '') 
                {
                    $destinationPath =  Storage::disk('public')->path('uploads/songs');
                    if(!empty($val->attachment)){
                        if(File::exists($destinationPath.'/'.$val->attachment)) {
                            $attachment = url('storage/app/public/uploads/songs/'.$val->attachment);
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

                // song banner image
                if ($val->banner_image != '') 
                {
                    $destinationPath =  Storage::disk('public')->path('uploads/song_banner_images');
                    if(!empty($val->banner_image)){
                        if(File::exists($destinationPath.'/'.$val->banner_image)) {
                            $banner_image = url('storage/app/public/uploads/song_banner_images/'.$val->banner_image);
                        }
                        else
                        {
                            $banner_image = "";
                        }
                    }
                    else
                    {
                        $banner_image = "";
                    }
                }
                else
                {
                    $banner_image = "";
                }


                $record_song[] = array(
                    "id"                    => $val->id,
                    "total_videos"          => $total_videos,
                    "song_id"               => $val->song_id,
                    "cat_id"                => $val->cat_id,
                    "name"                  => $val->name,
                    "description"           => $val->description,
                    "duration"              => $val->duration,
                    "singer_id"             => $val->singer_id,
                    "banner_image"          => $banner_image,
                    "attachment"            => $attachment,
                );
                $result = $record_song;
            }
            return response()->json(['data' => $result,'msg'=>'Song favortie to remove successfully!', 'status' =>'1']);
        }
        return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
    }
}
