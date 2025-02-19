<?php

namespace App\Http\Controllers;

use App\Models\VideoEffects;
use Illuminate\Http\Request;
use view;
use Validator;
use Storage;
use File;

class VideoEffectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $effects = VideoEffects::whereNull('deleted_at')->get();
        return View('effects.index',compact('effects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('effects.create');
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
            'name' => 'required',
            'attachment' => 'required',
        ]);

        if ($request->file('attachment') != '') 
        {
            // song file
            $attachment = $request->file('attachment');
            $destinationPath =  Storage::disk('public')->path('uploads/effects');
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

        $videoeffects              = new VideoEffects();
        $videoeffects->name        = $request->name;
        $videoeffects->attachment  = $attachment_fileName;
        $videoeffects->save();
        
        return redirect()->route('effects.index')->with('success', 'New effect added successfully.');
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
        $effect = VideoEffects::find($id); 
        if(!empty($effect)) 
        {   
            return View('effects.edit',compact('effect'));
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
            'name' => 'required',
        ]);

        $videoeffects = VideoEffects::find($id); 
        // echo "<pre>"; print_r($videoeffects->toArray()); die();
        // song file
        if ($request->file('attachment') != '') 
        {   
            $attachment = $request->file('attachment');
            $destinationPath =  Storage::disk('public')->path('uploads/effects');
            if(!empty($videoeffects) && !empty($videoeffects->attachment)){
                if(File::exists($destinationPath.'/'.$videoeffects->attachment)) {
                    $delete=File::delete($destinationPath.'/'.$videoeffects->attachment);
                }
            }

            $attachment_fileName   = time()."_".rand(11111,99999).".".$attachment->getClientOriginalExtension();
            $upload_success = $attachment->move($destinationPath, $attachment_fileName);
        }
        else
        {
            $attachment_fileName = $videoeffects->attachment;
        }

        $videoeffects->name        = $request->name;
        $videoeffects->attachment  = $attachment_fileName;
        $videoeffects->save();
        
        return redirect()->route('effects.index')->with('success', 'Effect details update successfully.');
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
        $effects = VideoEffects::find($request->id);
        if ($effects->status == 1) 
        {
            $effects->status = '0';
        }
        else
        {
            $effects->status = '1';
        }
        $effects->save();

        return redirect()->route('effects.index')->with('success', 'Status update successfully.');
    }

    
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        VideoEffects::find($request->id)->delete();
        return redirect()->route('effects.index')->with('success', 'Effect delete successfully.');
    }
}
