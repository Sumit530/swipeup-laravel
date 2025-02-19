<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Songs;
use App\Models\Singers;
use App\Models\Categories;
use view;
use Validator;
use Storage;
use File;

class SongsController extends Controller
{
    // public function __Construct() {
    //     return $this->middleware('auth');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $songs = Songs::leftJoin("categories","categories.id","=","songs.cat_id")
                        ->leftJoin("singers","singers.id","=","songs.singer_id")
                        ->select("songs.*","categories.name as categories_name","singers.name as singer_name")
                        ->get();
        return View('songs.index',compact('songs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Categories::whereNull('deleted_at')->get();
        $singers    = Singers::whereNull('deleted_at')->get();
         // echo "<pre>"; print_r($categories->toArray()); die();
        return View('songs.create',compact('categories','singers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'cat_id' => 'required',
            'name' => 'required',
            'banner_image' => 'required',
            'attachment' => 'required',
        ]);

        if ($request->file('banner_image') != '') 
        {
            // banner image file
            $banner_image = $request->file('banner_image');
            $destinationPath =  Storage::disk('public')->path('uploads/song_banner_images');
            if(!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath,0777, true, true);
            }

            $banner_image_fileName   = time()."_".rand(11111,99999).".".$banner_image->getClientOriginalExtension();
            $upload_success = $banner_image->move($destinationPath, $banner_image_fileName);
        }
        else
        {
            $banner_image_fileName = "";
        }

        if ($request->file('attachment') != '') 
        {
            // song file
            $attachment = $request->file('attachment');
            $destinationPath =  Storage::disk('public')->path('uploads/songs');
            if(!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath,0777, true, true);
            }

            $attachment_fileName   = time()."_".rand(11111,99999).".".$attachment->getClientOriginalExtension();
            $upload_success = $attachment->move($destinationPath, $attachment_fileName);
        }
        else
        {
            $attachment_fileName = "";
        }

        $songs = new Songs();
        $songs->cat_id      = $request->cat_id;
        $songs->singer_id   = isset($request->singer_id) ? $request->singer_id : '';
        $songs->name        = $request->name;
        $songs->duration    = isset($request->duration) ? $request->duration : '';
        $songs->banner_image= $banner_image_fileName;
        $songs->attachment  = $attachment_fileName;
        $songs->save();
        
        return redirect()->route('songs.index')->with('success', 'New song added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $songs = Songs::find($id); 
        if(!empty($songs)) 
        {   
            $categories = Categories::whereNull('deleted_at')->get();
            $singers    = Singers::whereNull('deleted_at')->get();
            return View('songs.edit',compact('songs','categories','singers'));
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
        $this->validate($request, [
            'cat_id' => 'required',
            'name' => 'required',
        ]);

        $songs = Songs::find($id); 
        // echo "<pre>"; print_r($songs->toArray()); die();
        // banner image
        if ($request->file('banner_image') != '') 
        {   
            $banner_image = $request->file('banner_image');
            $destinationPath =  Storage::disk('public')->path('uploads/song_banner_images');
            if(!empty($songs) && !empty($songs->banner_image)){
                if(File::exists($destinationPath.'/'.$songs->banner_image)) {
                    $delete=File::delete($destinationPath.'/'.$songs->banner_image);
                }
            }

            $banner_image_fileName   = time()."_".rand(11111,99999).".".$banner_image->getClientOriginalExtension();
            $upload_success = $banner_image->move($destinationPath, $banner_image_fileName);
        }
        else
        {
            $banner_image_fileName = $songs->banner_image;
        }

        // song file
        if ($request->file('attachment') != '') 
        {   
            $attachment = $request->file('attachment');
            $destinationPath =  Storage::disk('public')->path('uploads/songs');
            if(!empty($songs) && !empty($songs->attachment)){
                if(File::exists($destinationPath.'/'.$songs->attachment)) {
                    $delete=File::delete($destinationPath.'/'.$songs->attachment);
                }
            }

            $attachment_fileName   = time()."_".rand(11111,99999).".".$attachment->getClientOriginalExtension();
            $upload_success = $attachment->move($destinationPath, $attachment_fileName);
        }
        else
        {
            $attachment_fileName = $songs->attachment;
        }

        $songs->cat_id      = $request->cat_id;
        $songs->singer_id   = isset($request->singer_id) ? $request->singer_id : '';
        $songs->name        = $request->name;
        $songs->description = isset($request->description) ? $request->description : '';
        $songs->banner_image= $banner_image_fileName;
        $songs->attachment  = $attachment_fileName;
        $songs->save();
        
        return redirect()->route('songs.index')->with('success', 'Song details update successfully.');
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
        $songs = Songs::find($request->id);
        if ($songs->status == 1) 
        {
            $songs->status = '0';
        }
        else
        {
            $songs->status = '1';
        }
        $songs->save();

        return redirect()->route('songs.index')->with('success', 'Status update successfully.');
    }

    
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $songs = Songs::find($request->id)->delete();
        return redirect()->route('songs.index')->with('success', 'Song delete successfully.');
    }
}
