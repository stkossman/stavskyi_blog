<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource (Отримати список категорій).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);
        $search = $request->query('search');

        $query = BlogCategory::query();

        if ($search) {
            $query->where('title', 'LIKE', '%' . $search . '%')
            ->orWhere('slug', 'LIKE', '%' . $search . '%');
        }

        $categories = $query->orderBy('id', 'DESC')->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $categories->items(),
            'meta' => [
                'current_page' => $categories->currentPage(),
                'from' => $categories->firstItem(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'to' => $categories->lastItem(),
                'total' => $categories->total(),
            ],
            'links' => [
                'first' => $categories->url(1),
                'last' => $categories->url($categories->lastPage()),
                'prev' => $categories->previousPageUrl(),
                'next' => $categories->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage (Створити нову категорію).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:blog_categories,title',
            'slug' => 'nullable|unique:blog_categories,slug',
            'parent_id' => 'nullable|exists:blog_categories,id',
        ]);

        $data = $request->all();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $category = BlogCategory::create($data);

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource (Отримати одну категорію за ID).
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        try {
            $category = BlogCategory::findOrFail($id);
            return response()->json($category);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found.'], 404);
        }
    }

    /**
     * Update the specified resource in storage (Оновити існуючу категорію).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        try {
            $category = BlogCategory::findOrFail($id);

            $request->validate([
                'title' => 'required|string|min:3|max:200|unique:blog_categories,title,' . $id,
                'slug' => 'nullable|string|max:200|unique:blog_categories,slug,' . $id,
                'description' => 'nullable|string|max:500',
                'parent_id' => 'nullable|integer|exists:blog_categories,id',
            ]);

            $data = $request->all();

            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            $category->update($data);

            return response()->json($category);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found.'], 404);
        }
    }

    /**
     * Remove the specified resource from storage (Видалити категорію).
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $category = BlogCategory::findOrFail($id);

            $category->delete();

            return response()->json(['message' => 'Category deleted successfully.'], 204);
        } catch (ModelNotFoundException $e) {

            return response()->json(['message' => 'Category not found.'], 404);
        }
    }
}
