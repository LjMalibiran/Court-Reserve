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
        ['email' => 'BBC.Court.Reserve@gmail.com'],
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

Route::get('/merge-admins', function () {
    \App\Models\User::where('id', 3)->delete();
    $u = \App\Models\User::find(1);
    if ($u) {
        $u->email = 'BBC.Court.Reserve@gmail.com';
        $u->save();
        return "Admin merged!";
    }
    return "Admin 1 not found.";
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
    return response()->view('login')->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
})->middleware('guest')->name('login');

Route::get('/forgot-password', function () {
    return view('forgot-password');
})->middleware('guest')->name('forgot.password');
Route::post('/forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'sendResetCode'])->name('forgot.password.post');

Route::get('/forgot-password/verify', [\App\Http\Controllers\ForgotPasswordController::class, 'showVerifyReset'])->name('forgot.verify');
Route::post('/forgot-password/verify', [\App\Http\Controllers\ForgotPasswordController::class, 'verifyResetCode'])->name('forgot.verify.post');
Route::post('/forgot-password/resend', [\App\Http\Controllers\ForgotPasswordController::class, 'resendResetCode'])->name('forgot.resend');

Route::get('/forgot-password/reset', [\App\Http\Controllers\ForgotPasswordController::class, 'showResetPassword'])->name('forgot.reset');
Route::post('/forgot-password/reset', [\App\Http\Controllers\ForgotPasswordController::class, 'updatePassword'])->name('forgot.reset.post');

Route::get('/notifications/unread', [\App\Http\Controllers\NotificationController::class, 'unread'])->name('notifications.unread');
Route::post('/notifications/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');

Route::get('/api/reservations/by-date', [\App\Http\Controllers\ReservationController::class, 'getByDate']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

// Live Availability Check (Accessible to Users, Admins, and Cashiers)
Route::get('/api/check-availability', [ReservationController::class, 'checkAvailability']);
Route::get('/api/check-rentals', [ReservationController::class, 'checkRentals']);


// ==========================================
// 2. STAFF GATEWAY & LOGINS 
// ==========================================

Route::get('/staff/login', function () {
    return view('admin.selection');
})->name('staff.selection');

Route::get('/admin/forgot-password', function (\Illuminate\Http\Request $request) {
    $admin = \App\Models\User::where('role', 'admin')->first();
    if (!$admin) {
        return back()->withErrors(['login_id' => 'No admin account found.']);
    }
    
    // Auto-fill the email and forward the request to the ForgotPasswordController
    $request->merge(['email' => $admin->email]);
    return app(\App\Http\Controllers\ForgotPasswordController::class)->sendResetCode($request);
});

Route::get('/admin/login', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect('/admin/dashboard');
    }
    return response()->view('admin.login')->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0'); 
})->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

Route::get('/cashier/login', function () {
    if (Auth::check() && Auth::user()->role === 'cashier') {
        return redirect('/cashier/dashboard');
    }
    return response()->view('cashier.login')->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0'); 
})->name('cashier.login');


Route::redirect('/admin', '/admin/login');


// ==========================================
// 3. LOGGED IN, BUT NOT VERIFIED YET
// ==========================================

Route::middleware(['auth'])->group(function () {
    Route::get('/verify', function () {
        return view('verify');
    })->name('verify.index');

    Route::post('/verify', [VerificationController::class, 'verify'])->name('verify.post');
    Route::post('/verify/resend', [VerificationController::class, 'resend'])->name('verify.resend');
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
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->orderBy('created_at', 'desc')
            ->get();

        $upcomingReservations = \App\Models\Reservation::where('user_id', Auth::id())
            ->whereDate('start_time', '>', \Carbon\Carbon::today())
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('home', compact('todayReservations', 'upcomingReservations')); 
    })->name('home');

    // Reservation 
    Route::get('/reservation', function () { 
        $settingsPath = storage_path('app/settings.json');
        $settings = file_exists($settingsPath) ? json_decode(file_get_contents($settingsPath), true) : [
            'price_badminton' => 230,
            'price_pickleball' => 250,
            'price_racket' => 50,
            'price_shuttlecock' => 50,
        ];
        return view('reservation', compact('settings')); 
    })->name('reservation.index');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');

    // History Route
    Route::get('/history', function () {
        $historyReservations = \App\Models\Reservation::where('user_id', Auth::id())
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('history', compact('historyReservations')); 
    })->name('history.index');

    // Profile Route
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\ProfileController::class, 'changePassword'])->name('profile.password');
    Route::post('/profile/toggle-2fa', [\App\Http\Controllers\ProfileController::class, 'toggle2FA'])->name('profile.toggle-2fa');

    // Payment
    Route::get('/payment', function () { 
        $settingsPath = storage_path('app/settings.json');
        $settings = file_exists($settingsPath) ? json_decode(file_get_contents($settingsPath), true) : [
            'price_badminton' => 230,
            'price_pickleball' => 250,
            'price_racket' => 50,
            'price_shuttlecock' => 50,
        ];
        return view('payment', compact('settings')); 
    })->name('payment.index');
    Route::post('/reserve/process-payment', [ReservationController::class, 'processPayment']);

    // User Reservation Management
    Route::post('/reservations/{id}/edit-user', [ReservationController::class, 'editUserReservation']);
    Route::post('/reservations/{id}/cancel-user', [ReservationController::class, 'cancelUserReservation']);
    Route::post('/notifications/{id}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markSingleAsRead']);
});


// ==========================================
// 5. ADMIN SECURE AREA
// ==========================================

Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // QR Verification Routes
    Route::get('/admin/qr-verification', [AdminController::class, 'qrIndex']);
    Route::post('/admin/qr-verification/search', [AdminController::class, 'qrSearch']);
    Route::post('/admin/qr-verification/verify/{id}', [AdminController::class, 'qrVerify']);

    // Reservations
    Route::get('/admin/reservations', [AdminController::class, 'reservationsIndex']);
    Route::post('/admin/reservations/{id}/confirm', [AdminController::class, 'confirmReservation']);
    Route::post('/admin/reservations/{id}/cancel', [AdminController::class, 'cancelReservation']);
    Route::post('/admin/reservations/{id}/remind', [AdminController::class, 'sendReminder']);

    // Admin Walk-Ins
    Route::get('/admin/sales/filter', [App\Http\Controllers\AdminController::class, 'filterSales']);
    Route::get('/admin/walk-in', [ReservationController::class, 'walkInIndex']);
    Route::post('/admin/walk-in/store', [ReservationController::class, 'storeWalkIn']);
    Route::post('/admin/walk-in/save-receipt', [ReservationController::class, 'saveWalkInReceipt']);
    Route::post('/admin/walk-in/{id}/{status}', [ReservationController::class, 'updateWalkInStatus']);

    // Sales & Reports
    Route::get('/admin/sales-report', [AdminController::class, 'salesReportIndex']);
    Route::get('/admin/sales/refunds', [AdminController::class, 'salesRefundsIndex']);
    Route::post('/admin/sales/refunds/{id}/approve', [AdminController::class, 'approveRefund']);
    Route::post('/admin/sales/refunds/{id}/reject', [AdminController::class, 'rejectRefund']);

    // Settings & Profile
    Route::get('/admin/settings', [AdminController::class, 'settingsIndex']);
    Route::post('/admin/settings', [AdminController::class, 'updateSettings']);
    Route::get('/admin/profile', [AdminController::class, 'profileIndex']);
    Route::post('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::post('/admin/profile/password', [AdminController::class, 'updatePassword'])->name('admin.profile.password');
    Route::get('/admin/help', [AdminController::class, 'helpIndex']);

    // Manage Staff
    Route::get('/admin/staff', [\App\Http\Controllers\StaffController::class, 'index'])->name('admin.staff.index');
    Route::get('/admin/staff/{id}/attendance', [\App\Http\Controllers\StaffController::class, 'attendance'])->name('admin.staff.attendance');
    Route::post('/admin/staff', [\App\Http\Controllers\StaffController::class, 'store'])->name('admin.staff.store');
    Route::post('/admin/staff/{id}/update', [\App\Http\Controllers\StaffController::class, 'update'])->name('admin.staff.update');
    Route::post('/admin/staff/{id}/toggle-status', [\App\Http\Controllers\StaffController::class, 'toggleStatus'])->name('admin.staff.toggle-status');
    Route::post('/admin/staff/{id}/delete', [\App\Http\Controllers\StaffController::class, 'destroy'])->name('admin.staff.destroy');
});


// ==========================================
// 6. CASHIER SECURE AREA
// ==========================================

Route::middleware([\App\Http\Middleware\CashierMiddleware::class])->group(function () {

    Route::get('/cashier/dashboard', [CashierController::class, 'dashboard'])->name('cashier.dashboard');

    Route::get('/cashier/qr-verification', [CashierController::class, 'qrIndex']);
    Route::post('/cashier/qr-verification/search', [CashierController::class, 'qrSearch']);
    Route::post('/cashier/qr-verification/verify/{id}', [CashierController::class, 'qrVerify']);

    Route::get('/cashier/reservations', [CashierController::class, 'reservationsIndex']);
        Route::get('/cashier/transactions', [CashierController::class, 'transactionsIndex']);
    Route::post('/cashier/reservations/{id}/confirm', [CashierController::class, 'confirmReservation']);
    Route::post('/cashier/reservations/{id}/cancel', [CashierController::class, 'cancelReservation']);
    Route::post('/cashier/reservations/{id}/remind', [\App\Http\Controllers\AdminController::class, 'sendReminder']);

    // Cashier Walk-Ins
    Route::get('/cashier/sales/filter', [App\Http\Controllers\CashierController::class, 'filterSales']);
    Route::get('/cashier/walk-in', [ReservationController::class, 'walkInIndex']);
    Route::post('/cashier/walk-in/store', [ReservationController::class, 'storeWalkIn']);
    Route::post('/cashier/walk-in/save-receipt', [ReservationController::class, 'saveWalkInReceipt']);
    Route::post('/cashier/walk-in/{id}/{status}', [ReservationController::class, 'updateWalkInStatus']);

    Route::get('/cashier/sales-report', [App\Http\Controllers\CashierController::class, 'salesReportIndex']);
    Route::get('/cashier/sales/refunds', [App\Http\Controllers\CashierController::class, 'salesRefundsIndex']);
    Route::post('/cashier/sales/refunds/{id}/approve', [App\Http\Controllers\CashierController::class, 'approveRefund']);
    Route::post('/cashier/sales/refunds/{id}/reject', [App\Http\Controllers\CashierController::class, 'rejectRefund']);
    Route::get('/cashier/profile', function () { return view('cashier.profile'); })->name('cashier.profile');
    Route::post('/cashier/profile/photo', [CashierController::class, 'updateProfilePicture']);

});













Route::get('/force-admin', function () {
    $admin = \App\Models\User::updateOrCreate(
        ['name' => 'Court Reserve'],
        [
            'contact' => 'admin',
            'email' => 'admin@batangasbadminton.com',
            'password' => \Illuminate\Support\Facades\Hash::make('123Court'),
            'role' => 'admin',
            'is_active' => 1,
            'phone_verified_at' => now(),
        ]
    );
    return "SUCCESS! Admin account forced. Username: " . $admin->name . " | Password: 123Court";
});

// Temporary route to test the reminder email
Route::get('/test-email', function () {
    $res_columns = \Illuminate\Support\Facades\Schema::getColumnListing('reservations');
    $court_columns = \Illuminate\Support\Facades\Schema::getColumnListing('courts');
    
    // Check if there are any reservations with sport = pickleball
    $pickleball_count = in_array('sport', $res_columns) ? \App\Models\Reservation::where('sport', 'like', '%Pickleball%')->count() : 0;
    
    return [
        'reservation_columns' => $res_columns,
        'court_columns' => $court_columns,
        'pickleball_reservations_count' => $pickleball_count
    ];
});


Route::get('/view-logs', function () {
    if (file_exists(storage_path('logs/laravel.log'))) {
        return '<pre>' . htmlspecialchars(shell_exec('tail -n 100 ' . storage_path('logs/laravel.log'))) . '</pre>';
    }
    return 'No logs found.';
});
