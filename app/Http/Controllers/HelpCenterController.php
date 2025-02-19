<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HelpCenter;
use view;

class HelpCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $helpcenter = HelpCenter::query()->whereNull('deleted_at')->get();
        return View('helpcenter.index',compact('helpcenter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('helpcenter.create');
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
            'title' => 'required|unique:help_centers',
        ]);

        $helpcenter = new HelpCenter();
        $helpcenter->title      = $request->title;
        $helpcenter->details    = $request->details?$request->details:'';
        $helpcenter->save();
        
        return redirect()->route('helpcenter.index')->with('success', 'New help center added successfully.');
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
        $helpcenter = HelpCenter::find($id); 
        if(!empty($helpcenter)) 
        {
            return View('helpcenter.edit',compact('helpcenter'));
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
            'title' => 'required|unique:help_centers,title,'.$id,
        ]);

        $helpcenter             = new HelpCenter();
        $helpcenter->title      = $request->title;
        $helpcenter->details    = $request->details?$request->details:'';
        $helpcenter->save();
        
        return redirect()->route('helpcenter.index')->with('success', 'Help center details update successfully.');
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
        $helpcenter = HelpCenter::find($request->id);
        if ($helpcenter->status == 1) 
        {
            $helpcenter->status = '0';
        }
        else
        {
            $helpcenter->status = '1';
        }
        $helpcenter->save();

        return redirect()->route('helpcenter.index')->with('success', 'Status update successfully.');
    }

    
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $helpcenter = HelpCenter::find($request->id)->delete();
        return redirect()->route('helpcenter.index')->with('success', 'Help center delete successfully.');
    }
}
