<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;



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
    if(Auth::check()){
        $user = Auth::user();
        return view('welcome')
            ->with('user', $user->only(['lname', 'fname', 'mname', 'suffix', 'role', 'remark', 'office_id']));
    }
    return view('welcome');
});

Auth::routes([
    'login' => false
]);

Route::get('/init-user', function(){
    if(Auth::check()){
        return Auth::user();
    }
});

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'index']);
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/sign-up', [App\Http\Controllers\SignUpController::class, 'index']);

Route::post('/sign-up', [App\Http\Controllers\SignUpController::class, 'store']);


//set appointment
Route::post('/set-appointment', [App\Http\Controllers\SetAppointmentController::class, 'store']);




//ADDRESS
Route::get('/load-provinces', [App\Http\Controllers\AddressController::class, 'loadProvinces']);
Route::get('/load-cities', [App\Http\Controllers\AddressController::class, 'loadCities']);
Route::get('/load-barangays', [App\Http\Controllers\AddressController::class, 'loadBarangays']);




/*     ADMINSITRATOR/CPANEL      */
Route::resource('/cpanel', App\Http\Controllers\Administrator\CpanelController::class);

Route::resource('/users', App\Http\Controllers\Administrator\UserController::class);
Route::get('/get-users', [App\Http\Controllers\Administrator\UserController::class, 'getUsers']);
Route::get('/get-user-offices', [App\Http\Controllers\Administrator\UserController::class, 'getOffices']);

//APPOINTMENT
Route::resource('/appointments', App\Http\Controllers\Administrator\AppointmentController::class);
Route::get('/get-appointments', [App\Http\Controllers\Administrator\AppointmentController::class, 'getAppointments']);
Route::post('/appointment-approved/{id}', [App\Http\Controllers\Administrator\AppointmentController::class, 'approveAppointment']);


Route::resource('/training-centers', App\Http\Controllers\Administrator\TrainingCenterController::class);
Route::get('/get-training-centers', [App\Http\Controllers\Administrator\TrainingCenterController::class, 'getTrainingCenters']);

Route::get('/get-open-training-centers', [App\Http\Controllers\OpenTrainingCentersController::class, 'getOpenTrainingCenters']);

//Offices
Route::resource('/offices', App\Http\Controllers\Administrator\OfficeController::class);
Route::get('/get-offices', [App\Http\Controllers\Administrator\OfficeController::class, 'getOffices']);


Route::get('/get-open-ordinances', [App\Http\Controllers\CovidUpdatesController::class, 'getOrdinances']);


Route::get('/report-track', [App\Http\Controllers\Administrator\ReportTrackController::class, 'index']);
Route::get('/get-report-track', [App\Http\Controllers\Administrator\ReportTrackController::class, 'getReportTrack']);

//Offices Administrator (For office management)

/*     ADMINSITRATOR          */


//USER
Route::resource('/dashboard-user', App\Http\Controllers\User\DashboardUserController::class);
Route::get('/get-user', [App\Http\Controllers\User\DashboardUserController::class, 'getUser']);

Route::resource('/my-appointment', App\Http\Controllers\User\MyAppointmentController::class);
Route::get('/get-my-appointments', [App\Http\Controllers\User\MyAppointmentController::class, 'getMyAppointment']);
Route::post('/cancel-my-appointment/{id}', [App\Http\Controllers\User\MyAppointmentController::class, 'cancelMyAppointment']);


Route::resource('/my-profile', App\Http\Controllers\User\MyProfileController::class);
Route::get('/get-my-profile', [App\Http\Controllers\User\MyProfileController::class, 'getProfile']);

Route::get('/my-upcoming-appointment', [App\Http\Controllers\User\MyAppointmentController::class, 'upcomingAppointment']);






Route::get('/session', function(){
    return Session::all();
});


Route::get('/applogout', function(Request $req){
    \Auth::logout();
    $req->session()->invalidate();
    $req->session()->regenerateToken();


});
