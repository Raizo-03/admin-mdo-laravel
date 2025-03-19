<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AnnouncementController;;
use App\Http\Controllers\MessageController;
use App\Livewire\StudentsTable;


// Redirect root URL to login
Route::get('/', function () {
    return redirect()->route('login');
});


// Dashboard routers
Route::get('/dashboard', [UserController::class, 'countUsers']);
Route::get('/dashboard', [AdminController::class, 'countAdmins']);
Route::get('/dashboard', [AppointmentController::class, 'countAppointments']);
Route::get('/dashboard', [FeedbackController::class, 'countFeedbacks']);
Route::get('/dashboard', [AnnouncementController::class, 'countAnnouncements']);
Route::get('/dashboard', [MessageController::class, 'getUnreadMessageCount']); // Updated function name
Route::get('/appointments-data', [AppointmentController::class, 'getAppointmentsData']);
Route::get('/registrations-data', [UserController::class, 'getMonthlyUserRegistrations']);




Route::middleware(['web'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.index');
        })->name('dashboard');
    });
});


//Routing for User Management
Route::prefix('users')->group(function () {
    Route::get('/users/students', [UserController::class, 'students'])->name('users.students.index');
    Route::get('/users/admins', [AdminController::class, 'admins'])->name('users.admins.index');
    // Route::get('/doctors', [DoctorController::class, 'index'])->name('users.doctors.index');
    // Route::get('/nurses', [NurseController::class, 'index'])->name('users.nurses.index');
});

//Student Management Page Routing
Route::post('/users/update-status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
Route::get('/students', StudentsTable::class)->name('students.index');


?>
