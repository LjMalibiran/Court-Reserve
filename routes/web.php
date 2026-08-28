<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Court;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController; 
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CashierController;

// ==========================================
// 1. PUBLIC ROUTES & DATABASE SETUP
// ==========================================

Route::get('/', function () {
    return view('welcome');
});

Route::get('/setup-database', function () {
    \App\Models\User::updateOrCreate(
        ['email' => 'admin@batangas.com'],
        ['name' => 'CourtReserve', 'password' => Hash::make('123Court'), 'role' => 'admin']
    );
    \App\Models\User::updateOrCreate(
        ['email' => 'cashier@batangas.com'],
        ['name' => 'Lj Malibiran', 'password' => Hash::make('123Lj'), 'role' => 'cashier']
    );
    \App\Models\Court::updateOrCreate(['id' => 1], ['is_active' => true]);
    \App\Models\Court::updateOrCreate(['id' => 2], ['is_active' => true]);
    \App\Models\Court::updateOrCreate(['id' => 3], ['is_active' => true]);
    
    return 'Database successfully populated! You can now log in.';
});

Route::get('/check-admin', function () {
    try {
        $admin = \App\Models\User::where('role', 'admin')->first();
        if (!$admin) return "ERROR: No admin account exists!";
        return "SUCCESS! Admin found. <br> <strong>Login ID / Name:</strong> " . $admin->name . "<br><strong>Role:</strong> " . $admin->role;
    } catch (\Exception $e) {
        return "CRITICAL ERROR: " . $e->getMessage();
    }
});

// Normal User Login & Register
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');


// ==========================================
// 2. STAFF GATEWAY & LOGINS 
// ==========================================

Route::get('/staff/login', function () {
    return view('admin.selection');
})->name('staff.selection');

Route::get('/admin/login', function () {
    return view('admin.login'); 
})->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

Route::get('/cashier/login', function () {
    return view('cashier.login'); 
})->name('cashier.login');

Route::get('/cashier/sign-up', function () {
    return view('cashier.sign-up');
});

Route::redirect('/admin', '/admin/login');


// ==========================================
// 3. LOGGED IN, BUT NOT VERIFIED YET
// ==========================================

Route::middleware(['auth'])->group(function () {
    Route::get('/verify', function () {
        return view('verify');
    })->name('verify.index');

    Route::post('/verify', [VerificationController::class, 'verify'])->name('verify.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


// ==========================================
// 4. SECURE USER ROUTES (Logged In AND Verified)
// ==========================================

Route::middleware(['auth', 'verified.phone'])->group(function () {
    
    // Dashboard
    Route::get('/home', function () {
        $todayReservations = \App\Models\Reservation::where('user_id', Auth::id())
            ->whereDate('start_time', \Carbon\Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();

        $upcomingReservations = \App\Models\Reservation::where('user_id', Auth::id())
            ->whereDate('start_time', '>', \Carbon\Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('home', compact('todayReservations', 'upcomingReservations')); 
    })->name('home');

    // Reservation 
    Route::get('/reservation', function () { return view('reservation'); })->name('reservation.index');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');

    // History Route
    Route::get('/history', function () {
        $historyReservations = \App\Models\Reservation::where('user_id', Auth::id())
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderBy('start_time', 'desc')
            ->get();
        return view('history', compact('historyReservations')); 
    })->name('history.index');

    // Profile Route
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\ProfileController::class, 'changePassword'])->name('profile.password');
    Route::post('/profile/toggle-2fa', [\App\Http\Controllers\ProfileController::class, 'toggle2FA'])->name('profile.toggle-2fa');

    // Payment
    Route::get('/payment', function () { return view('payment'); })->name('payment.index');
    Route::post('/reserve/process-payment', [ReservationController::class, 'processPayment']);

    // User Reservation Management
    Route::post('/reservations/{id}/edit-user', [ReservationController::class, 'updateUserReservation']);
    Route::post('/reservations/{id}/cancel-user', [ReservationController::class, 'cancelUserReservation']);
    Route::post('/notifications/mark-read', [ReservationController::class, 'markNotificationsRead']);

    // Live Availability Check
    Route::get('/api/check-availability', [ReservationController::class, 'checkAvailability']);
});


// ==========================================
// 5. ADMIN SECURE AREA
// ==========================================

Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {

    // IMPORTANT: Now using the controller to fetch identical data!
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // QR Verification Routes
    Route::get('/admin/qr-verification', [AdminController::class, 'qrIndex']);
    Route::post('/admin/qr-verification/search', [AdminController::class, 'qrSearch']);
    Route::post('/admin/qr-verification/verify/{id}', [AdminController::class, 'qrVerify']);

    // Reservations & Walk-Ins
    Route::get('/admin/reservations', [AdminController::class, 'reservationsIndex']);
    Route::post('/admin/reservations/{id}/confirm', [AdminController::class, 'confirmReservation']);
    Route::post('/admin/reservations/{id}/cancel', [AdminController::class, 'cancelReservation']);
    Route::get('/admin/walk-in', [AdminController::class, 'walkInIndex']);

    // Sales & Reports
    Route::get('/admin/sales-report', [AdminController::class, 'salesReportIndex']);
    Route::get('/admin/sales/transactions', [AdminController::class, 'salesTransactionsIndex']);
    Route::get('/admin/sales/refunds', [AdminController::class, 'salesRefundsIndex']);

    // Settings & Profile
    Route::get('/admin/settings', [AdminController::class, 'settingsIndex']);
    Route::get('/admin/profile', [AdminController::class, 'profileIndex']);

    // Manage Staff
    Route::get('/admin/staff/create', [AdminController::class, 'createStaff'])->name('admin.staff.create');
    Route::post('/admin/staff/store', [AdminController::class, 'storeStaff'])->name('admin.staff.store');
});


// ==========================================
// 6. CASHIER SECURE AREA
// ==========================================

Route::middleware([\App\Http\Middleware\CashierMiddleware::class])->group(function () {

    // IMPORTANT: Now using the controller to fetch identical data!
    Route::get('/cashier/dashboard', [CashierController::class, 'dashboard'])->name('cashier.dashboard');

    Route::get('/cashier/qr-verification', [CashierController::class, 'qrIndex']);
    Route::post('/cashier/qr-verification/search', [CashierController::class, 'qrSearch']);
    Route::post('/cashier/qr-verification/verify/{id}', [CashierController::class, 'qrVerify']);

    Route::get('/cashier/reservations', [CashierController::class, 'reservationsIndex']);
    Route::post('/cashier/reservations/{id}/confirm', [CashierController::class, 'confirmReservation']);
    Route::post('/cashier/reservations/{id}/cancel', [CashierController::class, 'cancelReservation']);

    Route::get('/cashier/walk-in', function () { return view('cashier.walk-in'); });
    Route::get('/cashier/sales/transactions', function () { return view('cashier.transactions'); });
    Route::get('/cashier/sales/refunds', function () { return view('cashier.refunds'); });
    Route::get('/cashier/profile', function () { return view('cashier.profile'); })->name('cashier.profile');

});