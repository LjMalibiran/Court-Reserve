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
// 1. PUBLIC ROUTES (Anyone can see these)
// ==========================================

Route::get('/', function () {
    return view('welcome');
});

// --- PASTE THESE RIGHT HERE ---
Route::get('/setup-database', function () {
    \App\Models\User::updateOrCreate(
        ['email' => 'admin@batangas.com'],
        ['name' => 'Lj', 'password' => \Illuminate\Support\Facades\Hash::make('admin123'), 'role' => 'admin']
    );
    \App\Models\User::updateOrCreate(
        ['email' => 'cashier@batangas.com'],
        ['name' => 'Main Cashier', 'password' => \Illuminate\Support\Facades\Hash::make('cashier123'), 'role' => 'cashier']
    );
    \App\Models\Court::updateOrCreate(['id' => 1], ['sport' => 'Badminton', 'price_per_hour' => 230]);
    \App\Models\Court::updateOrCreate(['id' => 2], ['sport' => 'Pickleball', 'price_per_hour' => 230]);
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
// ------------------------------

// ==========================================
// 1. PUBLIC ROUTES (Anyone can see these)
// ==========================================

Route::get('/', function () {
    return view('welcome');
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
// 2. STAFF GATEWAY & LOGINS (Must be public!)
// ==========================================

// The Gateway Screen
Route::get('/staff/login', function () {
    return view('admin.selection');
})->name('staff.selection');

// Admin Login
Route::get('/admin/login', function () {
    return view('admin.login'); 
})->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

// Cashier Login
Route::get('/cashier/login', function () {
    return view('cashier.login'); 
})->name('cashier.login');

// Cashier Sign-Up (If they register themselves)
Route::get('/cashier/sign-up', function () {
    return view('cashier.sign-up');
});

Route::redirect('/admin', '/admin/login');


// ==========================================
// 3. LOGGED IN, BUT NOT VERIFIED YET
// ==========================================

Route::middleware(['auth'])->group(function () {
    
    // Show the verification page
    Route::get('/verify', function () {
        return view('verify');
    })->name('verify.index');

    // Handle the verification submission
    Route::post('/verify', [VerificationController::class, 'verify'])->name('verify.post');
    
    // Allow users to logout even if they aren't verified yet
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


// ==========================================
// 4. SECURE USER ROUTES (Logged In AND Verified)
// ==========================================

Route::middleware(['auth', 'verified.phone'])->group(function () {
    
   // Dashboard
    Route::get('/home', function () {
        // Fetch reservations for TODAY
        $todayReservations = \App\Models\Reservation::where('user_id', Auth::id())
            ->whereDate('start_time', \Carbon\Carbon::today())
            ->orderBy('start_time', 'asc')
            ->get();

        // Fetch reservations for FUTURE dates
        $upcomingReservations = \App\Models\Reservation::where('user_id', Auth::id())
            ->whereDate('start_time', '>', \Carbon\Carbon::today())
            ->orderBy('start_time', 'asc')
            ->get();

        return view('home', compact('todayReservations', 'upcomingReservations')); 
    })->name('home');

    // Reservation 
    Route::get('/reservation', function () {
        return view('reservation');
    })->name('reservation.index');
    
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');

    // History Route
    Route::get('/history', function () {
        return view('history'); 
    })->name('history.index');

    // Profile Route
    Route::get('/profile', function () {
        return view('profile'); 
    })->name('profile.index');

    // Payment
    Route::get('/payment', function () {
        return view('payment');
    })->name('payment.index');

    Route::post('/reserve/process-payment', [ReservationController::class, 'processPayment']);

    // Live Availability Check
    Route::get('/api/check-availability', [ReservationController::class, 'checkAvailability']);

    // User Reservation Management
    Route::post('/reservations/{id}/edit-user', [ReservationController::class, 'updateUserReservation']);
    Route::post('/reservations/{id}/cancel-user', [ReservationController::class, 'cancelUserReservation']);
    
    // Notifications
    Route::post('/notifications/mark-read', [ReservationController::class, 'markNotificationsRead']);
});


// ==========================================
// 5. ADMIN SECURE AREA (Only Admins allowed)
// ==========================================

Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {

    Route::get('/admin/dashboard', function () {
        $today = \Carbon\Carbon::today();

        $todayReservations = \App\Models\Reservation::with('user') 
            ->whereDate('start_time', $today)
            ->where('status', 'confirmed')
            ->orderBy('start_time', 'asc')
            ->get();

        $upcomingReservations = \App\Models\Reservation::with('user')
            ->whereDate('start_time', '>', $today)
            ->orderBy('start_time', 'asc')
            ->limit(5)
            ->get();

        $totalReserved = \App\Models\Reservation::count();
        $totalUsers = \App\Models\User::count(); 
        $pending = \App\Models\Reservation::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'todayReservations', 
            'upcomingReservations', 
            'totalReserved', 
            'totalUsers', 
            'pending'
        ));
    })->name('admin.dashboard');

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
// 6. CASHIER SECURE AREA (Only Cashiers allowed)
// ==========================================

Route::middleware([\App\Http\Middleware\CashierMiddleware::class])->group(function () {

    Route::get('/cashier/dashboard', function () {
        return view('cashier.dashboard'); 
    })->name('cashier.dashboard');

    Route::get('/cashier/qr-verification', function () {
        return view('cashier.qr-verification');
    });

    Route::get('/cashier/reservations', [CashierController::class, 'reservationsIndex']);
    Route::post('/cashier/reservations/{id}/confirm', [CashierController::class, 'confirmReservation']);
    Route::post('/cashier/reservations/{id}/cancel', [CashierController::class, 'cancelReservation']);

    Route::get('/cashier/walk-in', function () {
        return view('cashier.walk-in'); 
    });

    Route::get('/cashier/sales/transactions', function () {
        return view('cashier.transactions'); 
    });

    Route::get('/cashier/sales/refunds', function () {
        return view('cashier.refunds'); 
    });

    Route::get('/cashier/profile', function () {
        return view('cashier.profile'); 
    })->name('cashier.profile');

    Route::get('/setup-database', function () {
    // 1. Generate the Admin Account (Lj)
    \App\Models\User::updateOrCreate(
        ['email' => 'CourtReserve@batangas.com'],
        [
            'name' => 'CourtReserve', 
            'password' => \Illuminate\Support\Facades\Hash::make('123Court'),
            'role' => 'admin' // Make sure this matches your actual role column setup!
        ]
    );

    // 2. Generate the Cashier Account
    \App\Models\User::updateOrCreate(
        ['email' => 'cashier@batangas.com'],
        [
            'name' => 'Lj Malibiran',
            'password' => \Illuminate\Support\Facades\Hash::make('123Lj'),
            'role' => 'cashier'
        ]
    );

    // 3. Re-create the Courts (Otherwise reservations will crash!)
    \App\Models\Court::updateOrCreate(
        ['id' => 1],
        ['sport' => 'Badminton', 'price_per_hour' => 230]
    );
    
    \App\Models\Court::updateOrCreate(
        ['id' => 2],
        ['sport' => 'Pickleball', 'price_per_hour' => 230]
    );

    return 'Database successfully populated! You can now log in.';
});

Route::get('/check-admin', function () {
    try {
        $admin = \App\Models\User::where('role', 'admin')->first();
        
        if (!$admin) {
            return "ERROR: No admin account exists in the database! The generator failed.";
        }
        
        return "SUCCESS! Admin found. <br> 
                <strong>Login ID / Name:</strong> " . $admin->name . "<br>
                <strong>Email:</strong> " . $admin->email . "<br>
                <strong>Role:</strong> " . $admin->role;
                
    } catch (\Exception $e) {
        return "CRITICAL ERROR: " . $e->getMessage();
    }
});

});