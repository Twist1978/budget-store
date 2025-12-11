<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AccountController;

/*
|--------------------------------------------------------------------------
| API Routes (ohne Auth)
|--------------------------------------------------------------------------
|
| Diese Routen werden automatisch unter /api/... erreichbar.
| Kein Sanctum, keine Authentifizierung – ideal zum Starten.
|
*/

// Healthcheck / Info
Route::get('/health', function () {
    return response()->json([
        'app' => 'Budget Backend',
        'status' => 'ok',
    ]);
});

/*
|--------------------------------------------------------------------------
| Expenses
|--------------------------------------------------------------------------
| Standard-CRUD für Ausgaben:
|   GET    /api/expenses
|   POST   /api/expenses
|   GET    /api/expenses/{id}
|   PUT    /api/expenses/{id}
|   DELETE /api/expenses/{id}
*/
Route::apiResource('expenses', ExpenseController::class);

/*
|--------------------------------------------------------------------------
| Categories
|--------------------------------------------------------------------------
| Kategorien-Management:
|   GET    /api/categories
|   POST   /api/categories
|   PUT    /api/categories/{id}
|   DELETE /api/categories/{id}
|
| show() wird hier nicht benötigt, daher except(['show'])
*/
Route::apiResource('categories', CategoryController::class)->except(['show']);

/*
|--------------------------------------------------------------------------
| Accounts
|--------------------------------------------------------------------------
| Kategorien-Management:
|   GET    /api/accounts
|   POST   /api/accounts
|   PUT    /api/accounts/{id}
|   DELETE /api/accounts/{id}
|
| show() wird hier nicht benötigt, daher except(['show'])
*/
Route::apiResource('accounts', AccountController::class)->except(['show']);
