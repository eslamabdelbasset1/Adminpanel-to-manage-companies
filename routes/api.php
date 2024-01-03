<?php

use App\Http\Api\CompanyApiController;
use App\Http\Api\EmployeeApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/getEmployees', [EmployeeApiController::class,'getEmployees'])->name('api.employees');
Route::get('/getCompanies', [CompanyApiController::class,'getCompanies'])->name('api.companies');

Route::apiResource('employees', EmployeeApiController::class)->only(['index','show']);
Route::apiResource('companies', CompanyApiController::class)->only(['index','show']);



