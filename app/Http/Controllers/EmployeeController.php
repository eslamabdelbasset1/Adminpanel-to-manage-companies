<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('employee.index');

    }

    public function create()
    {
        $companies = Company::select('id','name')->latest()->get();
        return view('employee.create',compact('companies'));
    }

    public function store(EmployeeStoreRequest $request)
    {
        Employee::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'company_id' => $request->company_id,
            'email' => $request->email,
            'phone' => $request->phone
        ]);

        return redirect()->route('employees.create')->with('message',['text' => __('employee.status2'), 'class' => 'success']);
    }

    public function edit(Employee $employee)
    {
        $companies = Company::select('id','name')->get();
        return view('employee.edit',compact('employee','companies'));
    }

    public function update(EmployeeStoreRequest $request, Employee $employee)
    {
        $employee->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'company_id' => $request->company_id,
            'email' => $request->email,
            'phone' => $request->phone
        ]);

        return redirect()->route('employees.edit',$employee->id)
            ->with('message',['text' => __('employee.status3'), 'class' => 'success']);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('message',['text' => __('employee.status4'), 'class' => 'success']);
    }
}
