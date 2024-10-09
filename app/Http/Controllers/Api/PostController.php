<?php

namespace App\Http\Controllers\API;

use App\Models\Posts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Posts::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic_id' => 'required|exists:topics,id',
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $post = Posts::create($request->all());
            return response()->json([
                'status' => 'SUCCESS',
                'result' => $post
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $post = Posts::findOrFail($id);
            return response()->json([
                'status' => 'SUCCESS',
                'result' => $post
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'topic_id' => 'required|exists:topics,id',
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $post = Posts::findOrFail($id);
            $post->update($request->all());
            return response()->json([
                'status' => 'SUCCESS',
                'result' => $post
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $post = Posts::findOrFail($id);

            try {
                $post->delete();
                return response()->json([
                    'status' => 'SUCCESS',
                    'result' => $post
                ], 200);
            } catch (QueryException $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ], 500);
            }
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }
    }
}
