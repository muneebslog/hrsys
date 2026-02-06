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

Route::middleware(['auth'])->group(function () {

    // Admin Only Routes
    Route::middleware(['role:admin'])->group(function () {
        Volt::route('/admindashboard', 'admindashboard')->name('admin.dashboard');
        Volt::route('/staffdirectory', 'staffdirectory')->name('staffdirectory');
        Volt::route('/leaverequest', 'leaverequest')->name('leaverequests');
        Volt::route('/feedbacklogs', 'feedbacklogs')->name('feedlogs');
        Volt::route('/departmentcrud', 'departmentcrud')->name('departmentcrud');
        Volt::route('/leavetypecrud', 'leavetypecrud')->name('leavetypecrud');
        Volt::route('/adminguide', 'adminguide')->name('admin.guide');
        Volt::route('/panel', 'panel')->name('panel');
        Volt::route('/round-sections', 'roundsectionscrud')->name('rounds.sections');
        Volt::route('/rounds', 'roundsreport')->name('rounds.report');
        Volt::route('/rounds/{round}', 'rounddetail')->name('rounds.show');
        Volt::route('/duty-roster', 'dutyroster')->name('roster.index');
    });

    Volt::route('/my-roster', 'myroster')->name('roster.my');

    // Admin and Supervisor: Conduct round wizard
    Route::middleware(['role:admin,supervisor'])->group(function () {
        Volt::route('/conduct-round', 'conductround')->name('rounds.conduct');
    });

   //Route::middleware(['auth', 'role:doctor,employee,staff'])->group(function () {
    Volt::route('/stafftickets', 'stafftickets')->name('emp.tickets');
    Volt::route('/empdashboard', 'empdashboard')->name('emp.dashboard');
    Volt::route('/staffapplyleave', 'staffapplyleave')->name('staffapplyleave');
    Volt::route('/staffcomplaintscell', 'staffcomplaintscell')->name('staffcomplaints');
//});

 
    
    // Shared Routes (Both Admin and Employee can access)
    Volt::route('/empguide', 'empguide')->name('emp.guide');
    Volt::route('/employeeprofile/{emp}', 'employeeprofile')->name('emp.profile');
    Volt::route('/document/vault', 'documentvault')->name('docs');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
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
