<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyStoreRequest;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;

use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public $logoName;

    public function resize_logo($logo){

        $this->logoName = time() . '.' . $logo->extension();
        $path = $logo->storeAs('public/logos',$this->logoName);

        //resize logo with spatie
        Image::load(public_path('storage/logos') . '/' . $this->logoName)
            ->width(100)->height(100)->save();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $companies = Company::latest()->paginate(10);
        // return view('company.index',compact('companies'));

        return view('company.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyStoreRequest $request)
    {
        //store logo
        if($request->hasFile('logo')){
            $logo = $request->file('logo');
            //resize logo function
            $this->resize_logo($logo);
        }else{
            $this->logoName = 'default.jpg';
        }

        Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'logo' => $this->logoName,
            'website' => $request->website
        ]);

        return redirect()->route('companies.create')->with('message',['text' => __('company.status2'), 'class' => 'success']);
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
    public function edit(Company $company)
    {
        return view('company.edit',compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyStoreRequest $request, Company $company)
    {
        //store logo
        if($request->hasFile('logo')){
            $logo = $request->file('logo');
            //resize image function
            $this->resize_logo($logo);
        }else if($company->logo != null){
            $this->logoName = $company->logo;
        }else{
            $this->logoName = 'default.jpg';
        }

        $company->update([
            'name' => $request->name,
            'email' => $request->email,
            'logo' => $this->logoName,
            'website' => $request->website
        ]);

        return redirect()->route('companies.edit',$company->id)->with('message',['text' => __('company.status3'), 'class' => 'success']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('message',['text' => __('company.status4'), 'class' => 'success']);
    }
}
