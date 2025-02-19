<?php

namespace App\Http\Controllers;
use App\Models\Interests;
use Illuminate\Http\Request;

class InterestsController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $interests = Interests::query()->whereNull('deleted_at')->get();
        return View('interests.index',compact('interests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('interests.create');
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
            'name' => 'required|unique:interests',
        ]);

        $interests = new Interests();
        $interests->name = $request->name;
        $interests->save();
        
        return redirect()->route('interests.index')->with('success', 'New interest added successfully.');
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
        $interests = Interests::find($id); 
        if(!empty($interests)) 
        {
            return View('interests.edit',compact('interests'));
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
            'name' => 'required|unique:interests,name,'.$id,
        ]);

        $interests = Interests::find($id); 
        $interests->name = $request->name;
        $interests->save();
        
        return redirect()->route('interests.index')->with('success', 'Interest details update successfully.');
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
        $interests = Interests::find($request->id);
        if ($interests->status == 1) 
        {
            $interests->status = '0';
        }
        else
        {
            $interests->status = '1';
        }
        $interests->save();

        return redirect()->route('interests.index')->with('success', 'Status update successfully.');
    }

    
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        Interests::find($request->id)->delete();
        return redirect()->route('interests.index')->with('success', 'Interest delete successfully.');
    }
}
