<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = $request->user()->articles()->latest()->get();
        return response()->json([
            'articles' => $articles
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $article = $request->user()->articles()->create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Article created successfully',
            'article' => $article
        ], 201);
    }

    public function show(Article $article)
    {
        if (Gate::denies('view', $article)) {
            abort(403, 'Unauthorized action.');
        }

        return response()->json([
            'article' => $article
        ]);
    }

    public function update(Request $request, Article $article)
    {
        if (Gate::denies('update', $article)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $article->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Article updated successfully',
            'article' => $article
        ]);
    }

    public function destroy(Article $article)
    {
        if (Gate::denies('delete', $article)) {
            abort(403, 'Unauthorized action.');
        }

        $article->delete();

        return response()->json([
            'message' => 'Article deleted successfully'
        ]);
    }
}