<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * GET /api/accounts
     */
    public function index(): JsonResponse {
        return response()->json(
            Account::orderBy('name')->get()
        );
    }

    /**
     * POST /api/accounts
     */
    public function store(Request $request): JsonResponse {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'color' => 'nullable|string|max:20',
        ]);

        $account = Account::create($validated);

        return response()->json($account, 201);
    }

    /**
     * PUT/PATCH /api/accounts/{id}
     */
    public function update(Request $request, Account $account): JsonResponse {
        $validated = $request->validate([
            'name'  => 'sometimes|string|max:255',
            'color' => 'sometimes|string|max:20',
        ]);

        $account->update($validated);

        return response()->json($account);
    }

    /**
     * DELETE /api/accounts/{id}
     */
    public function destroy(Account $account): JsonResponse {
        $account->delete();

        return response()->json([
            'deleted' => true,
        ]);
    }
}
