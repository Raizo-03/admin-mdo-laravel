<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AnnouncementController;;
use App\Http\Controllers\MessageController;
use App\Livewire\StudentsTable;
use App\Http\Controllers\TriviaController;
use App\Models\Message;

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
Route::get('/feedback/chart-data', [FeedbackController::class, 'getChartData']);




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
    Route::get('/users/doctors', [AdminController::class, 'doctors'])->name('users.doctors.index');
    Route::get('/users/nurses', [AdminController::class, 'nurses'])->name('users.nurses.index');
    // Route::get('/doctors', [DoctorController::class, 'index'])->name('users.doctors.index');
    // Route::get('/nurses', [NurseController::class, 'index'])->name('users.nurses.index');
});

//Student Management Page Routing
Route::post('/users/update-status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
Route::get('/students', StudentsTable::class)->name('students.index');
Route::get('/user-profile/{user_id}', action: [UserProfileController::class, 'show']);
Route::put('/user-profile/{id}', [UserProfileController::class, 'update']);
Route::put('/admin/update/{id}', [AdminController::class, 'update'])->name('admin.update');
Route::get('/admin-profile/{id}', [AdminController::class, 'show']);
Route::put('/admin-profile/update/{id}', [AdminController::class, 'updateAdmin']);


//Admin Profile routing
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
});
Route::post('/admin/profile/update', [AdminController::class, 'updateProfilePicture'])->name('admin.profile.update');
Route::post('/admin/profile/update-info', [AdminController::class, 'updateInfo'])->name('admin.profile.updateInfo');





//Content Management / Trivia Routing
Route::get('/dashboard/contentmanagement/trivia', [TriviaController::class, 'index'])->name('trivia.index');

//Content Management / Feedback Routing
Route::get('/dashboard/contentmanagement/feedback', action: [FeedbackController::class, 'index'])->name('feedback.index');

//Content Management / Announcement Routing
Route::get('/dashboard/contentmanagement/announcement', action: [AnnouncementController::class, 'index'])->name('announcement.index');
Route::post('/announcements/store', [AnnouncementController::class, 'store'])->name('announcements.store');


//ContentManagaement / Chat Routing
Route::get('/admin/chat', function () {
    $users = Message::select('sender_email as email')
        ->where('receiver_email', 'admin2@example.com')
        ->orWhere('sender_email', 'admin2@example.com')
        ->distinct()
        ->get();

    return view('dashboard.contentmanagement.chat.index', compact('users'));
})->name('admin.chat');

Route::get('/messages/{userEmail}', [MessageController::class, 'fetchMessages'])->name('messages.fetch');
Route::post('/messages/send', [MessageController::class, 'sendMessage'])->name('messages.send');
Route::get('/users-with-messages', [MessageController::class, 'getUsersWithMessages'])->name('users.messages');
Route::get('/users-with-unread-messages', [MessageController::class, 'getUsersWithUnreadMessages'])
    ->name('users.unread-messages');
    

//Doctor and Nurse Routing
// Add a new admin with a doctor role
// Route::post('/admin/create-doctor', [AdminController::class, 'createDoctor'])->name('admin.create-doctor');

//Appointment Management
Route::get('/appointments/confirmed', [AppointmentController::class, 'confirmed'])->name('appointments.confirmed');
Route::get('/appointments/completed', [AppointmentController::class, 'completed'])->name('appointments.completed');
Route::get('/appointments/noshow', [AppointmentController::class, 'noShow'])->name('appointments.noshow');

//view approved appointments
Route::get('appointments/confirmed/{id}', [AppointmentController::class, 'show'])->name('appointments.confirmed.show');

//view completed appointments
Route::get('appointments/completed/{id}', [AppointmentController::class, 'showCompleted'])->name('appointments.completed.show');

//view no show appointments
Route::get('appointments/noshow/{id}', [AppointmentController::class, 'showNoShow'])->name('appointments.noShow.show');


//Vital Signs
Route::post('/vital-signs', [App\Http\Controllers\VitalSignsController::class, 'store'])->name('vital-signs.store');
Route::put('/vital-signs/{booking_id}', [App\Http\Controllers\VitalSignsController::class, 'update'])->name('vital-signs.update');

//Medical record
Route::post('/medical-records', [App\Http\Controllers\MedicalRecordController::class, 'store'])->name('medical-records.store');
Route::put('/medical-records/{booking_id}', [App\Http\Controllers\MedicalRecordController::class, 'update'])->name('medical-records.update');

//Follow up
Route::post('/appointments/followup', [App\Http\Controllers\AppointmentController::class, 'createFollowUp'])->name('appointments.followup');
Route::get('/appointments/followups/{email}', [AppointmentController::class, 'getFollowUpAppointments'])->name('appointments.followups');

//Historical patients record
//View student details
Route::get('users/students/{id}', action: [UserController::class, 'show'])->name('students.show');

//View student medical records specific
// Route::get('users/students/{id}', action: [UserController::class, 'show'])->name('students.showPast');
//Edit Student Details
Route::put('users/students', [UserController::class, 'update'])->name('dashboard.users.students.update');


?>
