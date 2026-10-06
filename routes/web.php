<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect('/login');
});

// View Login
Route::get('/login', function () {
    return view('login');
})->name('login');

// Process Login
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $role = strtolower(Auth::user()->role);

        if ($role === 'admin') {
            return redirect('/admin/users');
        } elseif ($role === 'storekeeper') {
            return redirect('/storekeeper/dashboard');
        } elseif (in_array($role, ['manager', 'site manager'])) {
            return redirect('/manager/dashboard');
        }

        return redirect('/login');
    }

    return back()->withErrors([
        'email' => 'Invalid login credentials.',
    ]);
});

// Group Routes with Authentication Middleware
Route::middleware(['auth'])->group(function () {

    // Admin Module Routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return redirect('/admin/users');
        });

        Route::get('/admin/users', [AdminController::class, 'indexUsers']);
        Route::post('/admin/users', [AdminController::class, 'storeUser']);
        Route::put('/admin/users/{user}', [AdminController::class, 'updateUser']);
        Route::patch('/admin/users/{user}/role', [AdminController::class, 'updateRole']);
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser']);
        Route::get('/admin/audit-trail', [AdminController::class, 'auditTrail']);
    });

    // Storekeeper & Site Manager Dashboards
    Route::get('/storekeeper/dashboard', function () {
        return view('storekeeper.dashboard');
    })->middleware('role:storekeeper');

    Route::get('/manager/dashboard', function () {
        return view('manager.dashboard');
    })->middleware('role:manager');

    // Materials Module Routes
    Route::get('/materials', [MaterialController::class, 'index']);
    Route::post('/materials', [MaterialController::class, 'store']);
    Route::patch('/materials/{material}/update-stock', [MaterialController::class, 'updateStock']);

    // Material Request Routes - Site Manager
    Route::get('/manager/requests', [RequestController::class, 'managerIndex'])->middleware('role:manager');
    Route::post('/manager/requests', [RequestController::class, 'store'])->middleware('role:manager');

    // Approval & Delivery Routes - Storekeeper
    Route::get('/storekeeper/requests', [RequestController::class, 'storekeeperIndex'])->middleware('role:storekeeper');
    
    // Status update endpoints (POST & PATCH supported)
    Route::post('/storekeeper/requests/{materialRequest}/status', [RequestController::class, 'updateStatus'])->middleware('role:storekeeper');
    Route::patch('/storekeeper/requests/{materialRequest}', [RequestController::class, 'updateStatus'])->middleware('role:storekeeper');

    // Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    });
});

// Temporary Seeding & Migration Route
Route::get('/run-seed-now', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        return 'SEEDED_SUCCESSFULLY!';
    } catch (\Exception $e) {
        return 'ERROR: ' . $e->getMessage();
    }
});