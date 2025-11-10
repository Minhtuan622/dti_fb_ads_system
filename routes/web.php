<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::view('project', 'projects.index')
    ->middleware(['auth', 'verified'])
    ->name('project');

Route::middleware(['auth', 'verified'])->group(function () {
    // Projects routes
    Route::get('projects', 'App\Http\Controllers\ProjectController@index')->name('projects.index');
    Route::get('projects/create', 'App\Http\Controllers\ProjectController@create')->name('projects.create');
    Route::post('projects', 'App\Http\Controllers\ProjectController@store')->name('projects.store');
    Route::get('projects/{project}/edit', 'App\Http\Controllers\ProjectController@edit')->name('projects.edit');
    Route::put('projects/{project}', 'App\Http\Controllers\ProjectController@update')->name('projects.update');
    Route::delete('projects/{project}', 'App\Http\Controllers\ProjectController@destroy')->name('projects.destroy');

    // Facebook accounts routes
    Route::get('facebook-accounts', 'App\\Http\\Controllers\\FacebookAccountController@index')->name('facebook-accounts.index');
    Route::get('facebook-accounts/create', 'App\\Http\\Controllers\\FacebookAccountController@create')->name('facebook-accounts.create');
    Route::post('facebook-accounts', 'App\\Http\\Controllers\\FacebookAccountController@store')->name('facebook-accounts.store');
    Route::get('facebook-accounts/{facebookAccount}/edit', 'App\\Http\\Controllers\\FacebookAccountController@edit')->name('facebook-accounts.edit');
    Route::put('facebook-accounts/{facebookAccount}', 'App\\Http\\Controllers\\FacebookAccountController@update')->name('facebook-accounts.update');
    Route::delete('facebook-accounts/{facebookAccount}', 'App\\Http\\Controllers\\FacebookAccountController@destroy')->name('facebook-accounts.destroy');

    // Ads routes
    Route::get('ads', 'App\\Http\\Controllers\\AdController@index')->name('ads.index');
    Route::get('ads/create', 'App\\Http\\Controllers\\AdController@create')->name('ads.create');
    Route::post('ads', 'App\\Http\\Controllers\\AdController@store')->name('ads.store');
    Route::get('ads/{ad}/edit', 'App\\Http\\Controllers\\AdController@edit')->name('ads.edit');
    Route::put('ads/{ad}', 'App\\Http\\Controllers\\AdController@update')->name('ads.update');
    Route::delete('ads/{ad}', 'App\\Http\\Controllers\\AdController@destroy')->name('ads.destroy');

    // Reports routes
    Route::get('reports', 'App\\Http\\Controllers\\ReportController@index')->name('reports.index');
    Route::get('reports/create', 'App\\Http\\Controllers\\ReportController@create')->name('reports.create');
    Route::post('reports', 'App\\Http\\Controllers\\ReportController@store')->name('reports.store');
    Route::get('reports/{report}/edit', 'App\\Http\\Controllers\\ReportController@edit')->name('reports.edit');
    Route::put('reports/{report}', 'App\\Http\\Controllers\\ReportController@update')->name('reports.update');
    Route::delete('reports/{report}', 'App\\Http\\Controllers\\ReportController@destroy')->name('reports.destroy');
    Route::post('reports/{report}/send-to-lark', 'App\\Http\\Controllers\\ReportController@sendToLark')->name('reports.send-to-lark');

    // Facebook Pages routes
    Route::get('facebook-pages', 'App\Http\Controllers\FacebookPageController@index')->name('facebook-pages.index');
    Route::get('facebook-pages/create', 'App\Http\Controllers\FacebookPageController@create')->name('facebook-pages.create');
    Route::post('facebook-pages', 'App\Http\Controllers\FacebookPageController@store')->name('facebook-pages.store');
    Route::get('facebook-pages/{facebook_page}/edit', 'App\Http\Controllers\FacebookPageController@edit')->name('facebook-pages.edit');
    Route::put('facebook-pages/{facebook_page}', 'App\Http\Controllers\FacebookPageController@update')->name('facebook-pages.update');
    Route::delete('facebook-pages/{facebook_page}', 'App\Http\Controllers\FacebookPageController@destroy')->name('facebook-pages.destroy');

    // Lark Settings routes
    Route::get('lark-settings', 'App\Http\Controllers\LarkSettingController@index')->name('lark-settings.index');
    Route::post('lark-settings', 'App\Http\Controllers\LarkSettingController@update')->name('lark-settings.update');
    Route::post('lark-settings/test', 'App\Http\Controllers\LarkSettingController@testWebhook')->name('lark-settings.test');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';
