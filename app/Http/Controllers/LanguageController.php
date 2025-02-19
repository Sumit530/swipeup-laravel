<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use view;

class LanguageController extends Controller
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
        $languages = Language::query()->whereNull('deleted_at')->get();
        return View('language.index',compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('language.create');
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
            'language_name' => 'required|unique:languages',
        ]);

        $language = new Language();
        $language->language_name = $request->language_name;
        $language->save();
        
        return redirect()->route('language.index')->with('success', 'New language added successfully.');
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
        $language = Language::find($id); 
        if(!empty($language)) 
        {
            return View('language.edit',compact('language'));
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
            'language_name' => 'required|unique:languages,language_name,'.$id,
        ]);

        $language = Language::find($id); 
        $language->language_name = $request->language_name;
        $language->save();
        
        return redirect()->route('language.index')->with('success', 'Language details update successfully.');
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
        $language = Language::find($request->id);
        if ($language->status == 1) 
        {
            $language->status = '0';
        }
        else
        {
            $language->status = '1';
        }
        $language->save();

        return redirect()->route('language.index')->with('success', 'Status update successfully.');
    }

    
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $language = Language::find($request->id)->delete();
        return redirect()->route('language.index')->with('success', 'Language delete successfully.');
    }
}
