<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('dashboard');
});


Route::get('login', [App\Http\Controllers\Auth\AuthController::class, 'index'])->name('login');
Route::post('post-login', [App\Http\Controllers\Auth\AuthController::class, 'postLogin'])->name('login.post');
Route::get('registration', [App\Http\Controllers\Auth\AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [App\Http\Controllers\Auth\AuthController::class, 'postRegistration'])->name('register.post');
Route::get('dashboard', [App\Http\Controllers\Auth\AuthController::class, 'dashboard']);
Route::get('logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

Route::post("api/create-task", "App\Http\Controllers\TaskController@createTask");
Route::post("api/update-task", "App\Http\Controllers\TaskController@updateTask");
Route::get("api/tasks", "App\Http\Controllers\TaskController@tasks");
Route::get("api/task/{task_id}", "App\Http\Controllers\TaskController@task");
Route::delete("api/task/{task_id}", "App\Http\Controllers\TaskController@deleteTask");
Route::delete("api/taskimg/{task_id}", "App\Http\Controllers\TaskController@deleteTaskImg");
Route::post("api/update-taskimg/{task_id}", "App\Http\Controllers\TaskController@updateTaskImg");


