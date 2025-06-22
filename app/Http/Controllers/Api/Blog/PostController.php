<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Метод для отримання списку блог-постів для API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        /** @var LengthAwarePaginator $posts */
        $posts = BlogPost::with(['user:id,name', 'category:id,title'])
            ->orderBy('id', 'DESC')
            ->paginate($perPage, ['*'], 'page', $page);

        $formattedPosts = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'is_published' => $post->is_published,
                'published_at' => $post->published_at ? \Carbon\Carbon::parse($post->published_at)->toIsoString() : null,
                'user' => ['name' => $post->user->name ?? ''],
                'category' => ['title' => $post->category->title ?? 'Без категорії']
            ];
        });

        return response()->json([
            'data' => $formattedPosts,
            'meta' => [
                'current_page' => $posts->currentPage(),
                'from' => $posts->firstItem(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'to' => $posts->lastItem(),
                'total' => $posts->total(),
            ],
            'links' => [
                'first' => $posts->url(1),
                'last' => $posts->url($posts->lastPage()),
                'prev' => $posts->previousPageUrl(),
                'next' => $posts->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $slug)
    {
        try {
            $post = BlogPost::where('slug', $slug)
                ->with(['user:id,name', 'category:id,title'])
                ->firstOrFail();

            $post->published_at = $post->published_at ? \Carbon\Carbon::parse($post->published_at)->toIsoString() : null;

            return response()->json($post);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Post not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'content_raw' => 'required|string',
                'excerpt' => 'nullable|string|max:500',
                'category_id' => 'required|exists:blog_categories,id',
                'is_published' => 'boolean',
                'published_at' => 'nullable|date',
            ]);

            $validatedData['slug'] = Str::slug($validatedData['title']);

            $originalSlug = $validatedData['slug'];
            $counter = 1;
            while (BlogPost::where('slug', $validatedData['slug'])->exists()) {
                $validatedData['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            $post = BlogPost::create($validatedData);

            $post->load(['user:id,name', 'category:id,title']);

            return response()->json([
                'message' => 'Post created successfully.',
                'data' => $post
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the post.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Генерація унікального slug для поста.
     *
     * @param  string  $title
     * @param  int|null  $excludeId
     * @return string
     */
    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (true) {
            $query = BlogPost::where('slug', $slug);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Оновлення існуючого блог-поста.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $slug)
    {
        try {
            $post = BlogPost::where('slug', $slug)->firstOrFail();

            $validatedData = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'content_raw' => 'sometimes|required|string',
                'excerpt' => 'nullable|string|max:500',
                'category_id' => 'sometimes|required|exists:blog_categories,id',
                'is_published' => 'boolean',
                'published_at' => 'nullable|date',
            ]);

            if (isset($validatedData['title']) && $validatedData['title'] !== $post->title) {
                $newSlug = Str::slug($validatedData['title']);

                $originalSlug = $newSlug;
                $counter = 1;
                while (BlogPost::where('slug', $newSlug)->where('id', '!=', $post->id)->exists()) {
                    $newSlug = $originalSlug . '-' . $counter;
                    $counter++;
                }

                $validatedData['slug'] = $newSlug;
            }

            $post->update($validatedData);

            $post->load(['user:id,name', 'category:id,title']);

            return response()->json([
                'message' => 'Post updated successfully.',
                'data' => $post
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Post not found.'], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the post.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $slug)
    {
        try {
            $post = BlogPost::where('slug', $slug)->firstOrFail();

            $post->delete();

            return response()->json([
                'message' => 'Post deleted successfully.'
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Post not found.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the post.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
