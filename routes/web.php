<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoalsController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\GetHelpController;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SavingsPlanController;



Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/gethelp', [GetHelpController::class, 'index'])->name('gethelp');
    Route::post('/gethelp/contact', [GetHelpController::class, 'contact'])->name('gethelp.contact');
    
    /*
    |--------------------------------------------------------------------------
    | Dashboard (Main Page with Analytics)
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/spending-data', [DashboardController::class, 'getSpendingData'])->name('dashboard.spending-data');
    
    
    /*
    |--------------------------------------------------------------------------
    | Transactions Management
    |--------------------------------------------------------------------------
    */
    // Main transaction routes (CRUD)
    Route::resource('transactions', TransactionController::class);
    
    // Bulk transaction insert
    Route::post('transactions/bulk/store', [TransactionController::class, 'storeBulk'])->name('transactions.storeBulk');
    
    // Soft delete features
    Route::get('transactions/trash/list', [TransactionController::class, 'trash'])->name('transactions.trash');
    Route::post('transactions/{id}/restore', [TransactionController::class, 'restore'])->name('transactions.restore');
    
    // Keep your old transaction view if needed
    Route::get('/transaction', function () {
        return redirect()->route('transactions.index');
    })->name('transaction');
    
    /*
    |--------------------------------------------------------------------------
    | Categories Management
    |--------------------------------------------------------------------------
    */
    Route::resource('categories', CategoryController::class)->except(['show']);
    
    /*
    |--------------------------------------------------------------------------
    | Cashflow Management
    |--------------------------------------------------------------------------
    */
    Route::get('/cashflow', function () {
        return view('cashflow');
    })->name('cashflow');
    
    // Cashflow data API for chart
    Route::get('/cashflow/data', [CashflowController::class, 'getData'])
    ->middleware('auth')
    ->name('cashflow.data');
    
    /*
    |--------------------------------------------------------------------------
    | Goals Management (formerly Savings Plans)
    |--------------------------------------------------------------------------
    */
    // Main goals routes (CRUD) - menggunakan parameter 'savingsPlan'
    Route::resource('goals', GoalsController::class)->parameters([
        'goals' => 'savingsPlan'
    ]);

    // Additional goal actions
    Route::patch('goals/{savingsPlan}/complete', [GoalsController::class, 'complete'])->name('goals.complete');
    Route::patch('goals/{savingsPlan}/cancel', [GoalsController::class, 'cancel'])->name('goals.cancel');
    Route::post('goals/{savingsPlan}/savings', [GoalsController::class, 'addSavings'])->name('goals.add-savings');
    

    /*
    |--------------------------------------------------------------------------
    | Account Management (Components)
    |--------------------------------------------------------------------------
    */
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::put('/account', [AccountController::class, 'update'])->name('account.update');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
    Route::delete('/account', [AccountController::class, 'destroy'])->name('account.destroy');

    Route::resource('savings', SavingsPlanController::class);
});


