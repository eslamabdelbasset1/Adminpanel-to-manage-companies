<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyStoreRequest;
use App\Models\Company;
use Spatie\Image\Image;

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

    public function index()
    {
        // $companies = Company::latest()->paginate(10);
        // return view('company.index',compact('companies'));
        return view('company.index');
    }

    public function create()
    {
        return view('company.create');
    }

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

    public function show($id)
    {
        //
    }

    public function edit(Company $company)
    {
        return view('company.edit',compact('company'));
    }

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

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('message',['text' => __('company.status4'), 'class' => 'success']);
    }
}
