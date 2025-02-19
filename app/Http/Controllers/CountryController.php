<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use view;

class CountryController extends Controller
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
        $countries = Country::query()->whereNull('deleted_at')->get();
        return View('country.index',compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('country.create');
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
            'name' => 'required|unique:countries',
        ]);

        $country = new Country();
        $country->name = $request->name;
        $country->save();
        
        return redirect()->route('country.index')->with('success', 'New country added successfully.');
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
        $country = Country::find($id); 
        if(!empty($country)) 
        {
            return View('country.edit',compact('country'));
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
            'name' => 'required|unique:countries,name,'.$id,
        ]);

        $country = Country::find($id); 
        $country->name = $request->name;
        $country->save();
        
        return redirect()->route('country.index')->with('success', 'Country details update successfully.');
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
        $country = Country::find($request->id);
        if ($country->status == 1) 
        {
            $country->status = '0';
        }
        else
        {
            $country->status = '1';
        }
        $country->save();

        return redirect()->route('country.index')->with('success', 'Status update successfully.');
    }


    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        $country = Country::find($request->id)->delete();

        return redirect()->route('country.index')->with('success', 'Country delete successfully.');
    }
}
