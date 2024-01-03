<?php

namespace App\Http\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CompanyApiController extends Controller
{
    public function getCompanies(){

        $company = Company::select('id','name','email','logo','website')->latest()->get();
        return DataTables::of($company)
            ->addIndexColumn()
            ->addColumn('image',function($company){
                $url = Storage::url('logos/' . $company->logo);
                return '<img src="'. $url .'" width="100" height="100" class="img-fluid" alt="logo">';
            })
            ->addColumn('action',function($company){
                return '<a href="/companies/'. $company->id .'/edit"
                    class="btn btn-primary btn-sm btn-block">Edit</a>
                    <a href="/companies/' . $company->id . '/destroy"
                    class="btn btn-danger btn-sm btn-block">Delete</a>';
            })
            ->rawColumns(['image','action'])
            ->toJson();
    }

    public function index(){
        $companies = Company::get();
        return CompanyResource::collection($companies);
    }

    public function show(Company $company){
        return new CompanyResource($company);
    }
}
