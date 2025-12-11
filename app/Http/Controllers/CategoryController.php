<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * GET /api/categories
     */
    public function index(): JsonResponse {
        return response()->json(
            Category::orderBy('name')->get()
        );
    }

    /**
     * POST /api/categories
     */
    public function store(Request $request): JsonResponse {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'color' => 'nullable|string|max:20',
        ]);

        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    /**
     * PUT/PATCH /api/categories/{id}
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'name'  => 'sometimes|string|max:255',
            'color' => 'sometimes|string|max:20',
        ]);

        $category->update($validated);

        return response()->json($category);
    }

    /**
     * DELETE /api/categories/{id}
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json([
            'deleted' => true,
        ]);
    }
}
