<?php

namespace App\Http\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Yajra\DataTables\Facades\DataTables;

class EmployeeApiController extends Controller
{
    public function getEmployees(){
        $employee = Employee::with(['company' => function($query){
            $query->select(['id','name']);
        }])->select('id','first_name','last_name','email','phone','company_id')->latest()->get();

        return DataTables::of($employee)
            ->addIndexColumn()
            ->addColumn('action',function($employee){
                return '<a href="/employees/'. $employee->id .'/edit"
                    class="btn btn-primary btn-sm btn-block">Edit</a>
                    <a href="/employees/' . $employee->id . '/destroy"
                    class="btn btn-danger btn-sm btn-block">Delete</a>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function index(){
        $employees = Employee::with('company')->get();
        return EmployeeResource::collection($employees);
    }

    public function show(Employee $employee){
        return new EmployeeResource($employee->load(['company']));
    }

}
