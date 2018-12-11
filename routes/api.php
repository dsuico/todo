<?php

use Illuminate\Http\Request;


Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');


Route::middleware(['auth:api'])->group(function () {
	Route::get('user', function(Request $request) {
		return $request->user();
	});
	Route::post('/logout', 'AuthController@logout');
	Route::get('schedules', 'ScheduleController@index');
	Route::post('schedules', 'ScheduleController@store');
	Route::get('schedules/{id}', 'ScheduleController@show');
	Route::put('schedules/{schedule}', 'ScheduleController@markAsCompleted');
	Route::post('tasks', 'TaskController@store');
	Route::put('tasks/{task}', 'TaskController@markAsCompleted');
});
