<?php

namespace App\Http\Controllers;

use App\Models\Singers;
use Illuminate\Http\Request;
use view;
use Validator;
use Storage;
use File;

class SingersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $singers = Singers::query()->whereNull('deleted_at')->get();
        return View('singers.index',compact('singers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function create()
    {
        return View('singers.create');
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
            // singer file
            $attachment = $request->file('attachment');
            $destinationPath =  Storage::disk('public')->path('uploads/singers');
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

        $singers = new Singers();
        $singers->name        = $request->name;
        $singers->description = isset($request->description) ? $request->description : '';
        $singers->image       = $attachment_fileName;
        $singers->save();
        
        return redirect()->route('singers.index')->with('success', 'New singer added successfully.');
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
        $singer = Singers::find($id); 
        if(!empty($singer)) 
        {   
            return View('singers.edit',compact('singer'));
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

        $singer = Singers::find($id); 
        // echo "<pre>"; print_r($singer->toArray()); die();
        // singer file
        if ($request->file('attachment') != '') 
        {   
            $attachment = $request->file('attachment');
            $destinationPath =  Storage::disk('public')->path('uploads/singers');
            if(!empty($singer) && !empty($singer->attachment)){
                if(File::exists($destinationPath.'/'.$singer->attachment)) {
                    $delete=File::delete($destinationPath.'/'.$singer->attachment);
                }
            }

            $attachment_fileName   = time()."_".rand(11111,99999).".".$attachment->getClientOriginalExtension();
            $upload_success = $attachment->move($destinationPath, $attachment_fileName);
        }
        else
        {
            $attachment_fileName = $singer->attachment;
        }

        $singer->name        = $request->name;
        $singer->description = isset($request->description) ? $request->description : '';
        $singer->image       = $attachment_fileName;
        $singer->save();
        
        return redirect()->route('singers.index')->with('success', 'Singer details update successfully.');
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
        $singer = Singers::find($request->id);
        if ($singer->status == 1) 
        {
            $singer->status = '0';
        }
        else
        {
            $singer->status = '1';
        }
        $singer->save();

        return redirect()->route('singers.index')->with('success', 'Status update successfully.');
    }

    
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $singer = Singers::find($request->id)->delete();
        return redirect()->route('singers.index')->with('success', 'Singer delete successfully.');
    }
}
