<?php

namespace App\Http\Controllers;
use App\Models\BannerImages;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Storage;
use File;

class BannerImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bannerimages = BannerImages::get();
        return View('bannerimages.index',compact('bannerimages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('bannerimages.create');
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
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ]);

        // banner image file
        $banner_image = $request->file('banner_image');
        $destinationPath =  Storage::disk('public')->path('uploads/banner_image');
        if(!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath,0777, true, true);
        }

        $fileName   = time()."_".rand(11111,99999).".".$banner_image->getClientOriginalExtension();
        $upload_success = $banner_image->move($destinationPath, $fileName);

        $bannerimages                  = new BannerImages();
        $bannerimages->image_name      = isset($fileName) ? $fileName :'';
        $bannerimages->save();

        return redirect()->route('bannerimages.index')->with('success', 'New banner image added successfully.');
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
        $bannerimages = BannerImages::find($id); 
        if(!empty($bannerimages)) 
        {
            return View('bannerimages.edit',compact('bannerimages'));
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
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ]);

        $bannerimages                  = BannerImages::find($id); 
        // delete old file
        $destinationPath =  Storage::disk('public')->path('uploads/banner_image');
        if(!empty($bannerimages) && !empty($bannerimages->image_name)){
            if(File::exists($destinationPath.'/'.$bannerimages->image_name)) {
                $delete=File::delete($destinationPath.'/'.$bannerimages->image_name);
            }
        }
        $banner_image = $request->file('banner_image');
        $fileName   = time()."_".rand(11111,99999).".".$banner_image->getClientOriginalExtension();
        $upload_success = $banner_image->move($destinationPath, $fileName);

        $bannerimages->image_name      = isset($fileName) ? $fileName :'';
        $bannerimages->save();
        
        return redirect()->route('bannerimages.index')->with('success', 'Banner image details update successfully.');
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
        $bannerimages = BannerImages::find($request->id);
        if ($bannerimages->status == 1) 
        {
            $bannerimages->status = '0';
        }
        else
        {
            $bannerimages->status = '1';
        }
        $bannerimages->save();

        return redirect()->route('bannerimages.index')->with('success', 'Status update successfully.');
    }

    
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $bannerimages                  = BannerImages::find($request->id); 
        // delete old file
        $deldestinationPath =  Storage::disk('public')->path('uploads/banner_image');
        if(!empty($bannerimages) && !empty($bannerimages->image_name)){
            if(File::exists($deldestinationPath.'/'.$bannerimages->image_name)) {
                $delete=File::delete($deldestinationPath.'/'.$bannerimages->image_name);
            }
        }
        BannerImages::find($request->id)->delete();
        return redirect()->route('bannerimages.index')->with('success', 'Banner image delete successfully.');
    }
}
