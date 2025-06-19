<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
                'published_at' => $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d.M H:i') : '',
                'user' => ['name' => $post->user->name],
                'category' => ['title' => $post->category->title],
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

            return response()->json($post);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Post not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }
}
