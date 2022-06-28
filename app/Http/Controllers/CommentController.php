<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['api']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $article_id)
    {
        $validator = Validator::make($request->only('comment'), [
            'comment' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $comment = Auth::user()->comments()->create([
            'article_id' => $article_id,
            'comment' => $request->comment
        ]);

        if ($comment) {
            return response()->json([
                'success' => true,
                'message' => 'Comment stored successfully.',
                'data' => $comment
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to store comment.'
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $validator = Validator::make($request->only('comment'), [
            'comment' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $update = $comment->update([
            'comment' => $request->comment
        ]);

        if ($update) {
            return response()->json([
                'success' => true,
                'message' => 'Comment updated successfully.',
                'data' => $comment
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update comment.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
