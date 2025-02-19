<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Videos;
use App\Models\VideoData;
use view;
use DB;

class VideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $videos = Videos::leftJoin("users","videos.user_id","=","users.id")
        ->whereNull('videos.deleted_at')
        ->select('videos.*',"users.name as user_name","users.username as user_username")
        ->get();
        // $videos = Videos::query()->whereNull('deleted_at')->get();
        return View('video.index',compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('videos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        // $this->validate($request, [
        //     'title' => 'required|unique:videos',
        // ]);

        $video = new Videos();
        $video->title      = $request->title;
        $video->details    = $request->details?$request->details:'';
        $video->save();
        
        return redirect()->route('videos.index')->with('success', 'New video added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $video = Videos::find($id); 
        $video = Videos::leftJoin("users","videos.user_id","=","users.id")
            ->leftJoin("video_likes","videos.id","=","video_likes.video_id")
            ->leftJoin("video_comments","videos.id","=","video_comments.video_id")
            ->select('videos.*',"users.name as user_name","users.username as user_username",DB::raw("count(video_likes.video_id) as total_likes"),DB::raw("count(video_comments.video_id) as total_comments"))
            ->where('videos.id',$id)
            ->first();
        // $video_list = VideoData::leftJoin("video_likes","video_data.id","=","video_likes.video_id")
        // ->leftJoin("video_comments","video_data.id","=","video_comments.video_id")
        // ->where('video_data.video_id',$id)
        // ->whereNull('video_data.deleted_at')
        // ->select('video_data.*',DB::raw("count(video_likes.video_id) as total_likes"),DB::raw("count(video_comments.video_id) as total_comments"))
        // ->groupBy('video_data.id')
        // ->get();
            // echo "<pre>"; print_r($video); die();
        // echo "<pre>"; print_r($video_list); die();
        if(!empty($video)) 
        {
            return View('video.details',compact('video'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $video_data = Videos::find($id); 
        if(!empty($video_data)) 
        {   
            $video = Videos::leftJoin("users","videos.user_id","=","users.id")
            ->where('videos.video_id',$id)
            ->select('video_data.*',"users.name as user_name")
            ->first();
            echo "<pre>"; print_r($video); die();
            return View('video.edit',compact('video'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $this->validate($request, [
        //     'title' => 'required|unique:videos,title,'.$id,
        // ]);

        $video             = new Videos();
        $video->title      = $request->title;
        $video->details    = $request->details?$request->details:'';
        $video->save();
        
        return redirect()->route('videos.index')->with('success', 'Video details update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function status(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        $video = Videos::find($request->id);
        if ($video->status == 1) 
        {
            $video->status = '0';
        }
        else
        {
            $video->status = '1';
        }
        $video->save();

        return redirect()->route('videos.index')->with('success', 'Status update successfully.');
    }

    public function video_status(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'video_id' => 'required',
        ]);
        $video = VideoData::find($request->id);
        if ($video->status == 1) 
        {
            $video->status = '0';
        }
        else
        {
            $video->status = '1';
        }
        $video->save();

        return redirect()->route('videos.show',array($request->video_id))->with('success', 'Status update successfully.');
    }

    
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $video = Videos::find($request->id)->delete();
        return redirect()->route('videos.index')->with('success', 'Video center delete successfully.');
    }

    public function video_delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'video_id' => 'required',
        ]);

        $video = VideoData::find($request->id)->delete();
        return redirect()->route('videos.show',array($request->video_id))->with('success', 'Video center delete successfully.');
    }
}
