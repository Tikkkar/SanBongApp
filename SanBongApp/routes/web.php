<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PitchController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TournamentController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Pages
Route::get('/pitches', [PitchController::class, 'index']);
Route::get('/pitches/{pitch}', [PitchController::class, 'show']);
Route::get('/tournaments', [TournamentController::class, 'index']);
Route::get('/tournaments/{tournament}', [TournamentController::class, 'show'])->name('tournaments.show');

// Protected User Routes (Customers & Admin)
Route::middleware('auth')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
    
    // Team Registration
    Route::get('/tournaments/{tournament}/teams/create', [\App\Http\Controllers\TeamController::class, 'create'])->name('teams.create');
    Route::post('/tournaments/{tournament}/teams', [\App\Http\Controllers\TeamController::class, 'store']);
});

// Admin Only Routes
Route::middleware('auth')->middleware(\App\Http\Middleware\EnsureUserIsAdmin::class)->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
            $revenueQuery = \App\Models\Booking::where('status', 'confirmed');
            
            if ($request->has('revenue_date') && $request->revenue_date != '') {
                $revenueQuery->whereDate('start_time', $request->revenue_date);
            }
            
            $totalRevenue = $revenueQuery->sum('total_price');
            $revenueDate = $request->get('revenue_date', '');
            
            return view('admin.dashboard', compact('totalRevenue', 'revenueDate'));
        })->name('admin.dashboard');

        // Admin Bookings
        Route::get('/bookings', [BookingController::class, 'adminIndex'])->name('admin.bookings.index');
        Route::get('/bookings/create', [BookingController::class, 'adminCreate'])->name('admin.bookings.create');
        Route::post('/bookings/create', [BookingController::class, 'adminStore'])->name('admin.bookings.store');
        Route::post('/bookings/{booking}/approve', [BookingController::class, 'approve']);
        
        // Admin Pitches Management
        Route::get('/pitches/create', [PitchController::class, 'create'])->name('admin.pitches.create');
        Route::post('/pitches', [PitchController::class, 'store']);
        Route::get('/pitches/{pitch}/edit', [PitchController::class, 'edit']);
        Route::put('/pitches/{pitch}', [PitchController::class, 'update']);
        Route::delete('/pitches/{pitch}', [PitchController::class, 'destroy']);
        Route::put('/pitches/{pitch}/toggle-active', [PitchController::class, 'toggleActive'])->name('admin.pitches.toggle-active');
        
        // Admin Pitch Prices
        Route::get('/pitches/{pitch}/prices', [PitchController::class, 'pricesIndex']);
        Route::post('/pitches/{pitch}/prices', [PitchController::class, 'storePrice']);
        Route::delete('/pitches/{pitch}/prices/{price}', [PitchController::class, 'destroyPrice']);
        
        // Admin Tournaments Management
        Route::get('/tournaments/create', [TournamentController::class, 'create'])->name('admin.tournaments.create');
        Route::post('/tournaments', [TournamentController::class, 'store']);
        Route::get('/tournaments/{tournament}/edit', [TournamentController::class, 'edit']);
        Route::put('/tournaments/{tournament}', [TournamentController::class, 'update']);
        Route::delete('/tournaments/{tournament}', [TournamentController::class, 'destroy']);
        
        // Admin Matches Management
        Route::get('/tournaments/{tournament}/matches/create', [\App\Http\Controllers\FootballMatchController::class, 'create']);
        Route::post('/tournaments/{tournament}/matches', [\App\Http\Controllers\FootballMatchController::class, 'store']);
        Route::post('/tournaments/{tournament}/matches/generate', [\App\Http\Controllers\FootballMatchController::class, 'generateFixtures'])->name('admin.matches.generate');
        Route::get('/matches/{match}/edit', [\App\Http\Controllers\FootballMatchController::class, 'editScore']);
        Route::put('/matches/{match}', [\App\Http\Controllers\FootballMatchController::class, 'updateScore']);
        
        // Admin Teams Management
        Route::get('/tournaments/{tournament}/teams/create', [\App\Http\Controllers\TeamController::class, 'adminCreate'])->name('admin.teams.create');
        Route::post('/tournaments/{tournament}/teams', [\App\Http\Controllers\TeamController::class, 'adminStore'])->name('admin.teams.store');
        Route::post('/teams/{team}/approve', [\App\Http\Controllers\TeamController::class, 'approve']);
        Route::post('/teams/{team}/reject', [\App\Http\Controllers\TeamController::class, 'reject']);
        Route::post('/teams/{team}/eliminate', [\App\Http\Controllers\TeamController::class, 'eliminate'])->name('admin.teams.eliminate');
        Route::post('/teams/{team}/restore', [\App\Http\Controllers\TeamController::class, 'restore'])->name('admin.teams.restore');
        Route::post('/teams/{team}/assign-group', [\App\Http\Controllers\TeamController::class, 'assignGroup'])->name('admin.teams.assign-group');

        // Admin User Management
        Route::get('/users', [\App\Http\Controllers\AdminUserController::class, 'index'])->name('admin.users.index');
        Route::delete('/users/{user}', [\App\Http\Controllers\AdminUserController::class, 'destroy'])->name('admin.users.destroy');
        Route::put('/users/{user}/toggle-role', [\App\Http\Controllers\AdminUserController::class, 'toggleRole'])->name('admin.users.toggle-role');
    });
});
