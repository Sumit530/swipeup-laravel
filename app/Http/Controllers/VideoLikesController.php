<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoLikes;
use view;

class VideoLikesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $video_likes = VideoLikes::leftJoin('users','users.id','=','video_likes.user_id')
            ->where('video_id',$request->id)
            ->select('video_likes.*','users.username as username','users.name as name')
            ->get();
        // echo "<pre>"; print_r($video_likes); die();
        return View('video.videolikes.index',compact('video_likes'));
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        VideoLikes::find($request->id)->delete();
        return redirect()->route('videolikes.index')->with('success', 'Video like delete successfully.');
    }
}
