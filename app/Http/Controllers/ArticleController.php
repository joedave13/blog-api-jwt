<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['api'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::with(['user'])->paginate(5);

        return response()->json([
            'success' => true,
            'message' => 'Articles retrieved successfully.',
            'data' => $articles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->except('_token'), [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $article = Auth::user()->articles()->create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'body' => $request->body
        ]);

        if ($article) {
            return response()->json([
                'success' => true,
                'message' => 'Article created successfully.',
                'data' => $article
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create article.'
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        $article->load(['user', 'comments']);

        return response()->json([
            'success' => true,
            'message' => 'Article retrieved successfully.',
            'data' => $article
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $validator = Validator::make($request->except('_token'), [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $update = $article->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'body' => $request->body
        ]);

        if ($update) {
            return response()->json([
                'success' => true,
                'message' => 'Article updated successfully.',
                'data' => $article
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update article.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $delete = $article->delete();

        if ($delete) {
            return response()->json([
                'success' => true,
                'message' => 'Article deleted successfully.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete article.'
            ], 500);
        }
    }
}
