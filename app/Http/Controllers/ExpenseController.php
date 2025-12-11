<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * GET /api/expenses
     */
    public function index(): JsonResponse {
        return response()->json(
            Expense::orderBy('date', 'desc')->get()
        );
    }

    /**
     * POST /api/expenses
     */
    public function store(Request $request): JsonResponse {
        $validated = $request->validate([
            'vendor'       => 'required|string|max:255',
            'amount'       => 'required|numeric',
            'date'         => 'required|date',
            'category_id'  => 'nullable|integer|exists:categories,id',
            'debit_account_id'=> 'required|integer|exists:accounts,id',

        ]);

        $expense = Expense::create($validated);

        return response()->json($expense, 201);
    }

    /**
     * GET /api/expenses/{id}
     */
    public function show(Expense $expense): JsonResponse {
        return response()->json($expense);
    }

    /**
     * PUT/PATCH /api/expenses/{id}
     */
    public function update(Request $request, Expense $expense): JsonResponse {
        $validated = $request->validate([
            'vendor'       => 'sometimes|string',
            'amount'       => 'sometimes|numeric',
            'date'         => 'sometimes|date',
            'category_id'  => 'sometimes|integer|exists:categories,id',
            'debit_account_id'=> 'sometimes|integer|exists:accounts,id',
        ]);

        $expense->update($validated);

        return response()->json($expense);
    }

    /**
     * DELETE /api/expenses/{id}
     */
    public function destroy(Expense $expense): JsonResponse {
        $expense->delete();

        return response()->json([
            'deleted' => true,
        ]);
    }
}
