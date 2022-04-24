<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Unauthorized
Route::get('/401', [AuthController::class, 'unauthorized'])->name('login');

//Auth
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);


Route::middleware('auth:api')->group(function(){

    Route::post('/auth/validate', [AuthController::class, 'validateToken']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    //Users
    Route::get('/users', [UserController::class, 'getAll']);
    Route::get('/user/{id}', [UserController::class, 'getOne']);
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'delete']);

    //providers
    Route::post('/provider', [ProviderController::class, 'add']);
    Route::get('/providers', [ProviderController::class, 'getAll']);
    Route::get('/provider/{id}', [ProviderController::class, 'getOne']);
    Route::put('/provider/{id}', [ProviderController::class, 'update']);
    Route::delete('/provider/{id}', [ProviderController::class, 'delete']);

    //clients
    Route::post('/client', [ClientController::class, 'add']);
    Route::get('/clients', [ClientController::class, 'getAll']);
    Route::get('/client/{id}', [ClientController::class, 'getOne']);
    Route::put('/client/{id}', [ClientController::class, 'update']);
    Route::delete('/client/{id}', [ClientController::class, 'delete']);

    //employees
    Route::post('/employee', [EmployeeController::class, 'add']);
    Route::get('/employees', [EmployeeController::class, 'getAll']);
    Route::get('/employee/{id}', [EmployeeController::class, 'getOne']);
    Route::put('/employee/{id}', [EmployeeController::class, 'update']);
    Route::delete('/employee/{id}', [EmployeeController::class, 'delete']);

    //inventory
    Route::post('/inventory', [InventoryController::class, 'add']);
    Route::get('/inventory', [InventoryController::class, 'getAll']);
    Route::get('/inventory/{group}', [InventoryController::class, 'getGroup']);
    Route::put('/inventory/{id}', [InventoryController::class, 'update']);
    Route::delete('inventory/{id}', [InventoryController::class, 'delete']);

    //projects
    Route::post('/project', [ProjectController::class, 'add']);
    Route::get('/projects', [ProjectController::class, 'getAll']);
    Route::get('/project/[id}', [ProjectController::class, 'getOne']);
    Route::get('/project/{group}', [ProjectController::class, 'getGroup']);
    Route::put('/project/{id}', [ProjectController::class, 'update']);
    Route::delete('project/{id}', [ProjectController::class, 'delete']);


    //expenses
    Route::post('/expense', [ExpenseController::class, 'add']);
    Route::get('/expenses', [ExpenseController::class, 'getAll']);
    Route::get('/expense/{id}', [ExpenseController::class, 'getOne']);
    Route::put('/expense/{id}', [ExpenseController::class, 'update']);
    Route::delete('/expense/{id}', [ExpenseController::class, 'delete']);
});
