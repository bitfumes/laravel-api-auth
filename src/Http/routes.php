<?php

Route::post('register', 'AuthController@register')->name('user.register');
Route::post('login', 'LoginController@login')->name('user.login');
Route::post('logout', 'LoginController@logout')->name('logout');
Route::get('user', 'AuthController@getUser')->name('user.get');
Route::patch('user', 'AuthController@update')->name('user.update');

// email verification
Route::post('/email/verify/resend', 'VerifyEmailController@resend')->name('verification.resend');
Route::post('/email/verify/{id}', 'VerifyEmailController@verifyEmail')->name('verification.verify');

// Password Resets
Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('user.password.email');
Route::post('/password/reset', 'ResetPasswordController@reset')->name('user.password.request');
Route::post('/password/update', 'ResetPasswordController@updatePassword')->name('user.password.update');
