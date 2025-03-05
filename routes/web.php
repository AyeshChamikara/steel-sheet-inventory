<?php
 
 use App\Http\Controllers\SteelSheetController;
 use App\Http\Controllers\TransactionController;
 

Route::get('/', [SteelSheetController::class, 'home'])->name('home');
Route::resource('steel-sheets', SteelSheetController::class);
//Route::get('/', [SteelSheetController::class, 'index'])->name('home');


Route::get('/dashboard/{type}', [SteelSheetController::class, 'typeDashboard'])->name('type.dashboard'); 
 // Steel Sheets Routes
 Route::resource('steel-sheets', SteelSheetController::class);
 Route::get('/steel-sheets-dashboard', [SteelSheetController::class, 'dashboard'])->name('steel-sheets.dashboard');
 Route::delete('/steel-sheets/{id}', [SteelSheetController::class, 'destroy'])->name('steel-sheets.destroy');

  // Transactions Routes
 Route::resource('transactions', TransactionController::class);

 Route::post('/transactions/add/{id}', [TransactionController::class, 'add'])->name('transactions.add');
 Route::post('/transactions/subtract/{id}', [TransactionController::class, 'subtract'])->name('transactions.subtract');