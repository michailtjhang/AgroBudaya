<?php

namespace App\Http\Controllers\API;

use App\Models\Comments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Comments::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $comment = Comments::create($request->all());
            return response()->json([
                'status' => 'SUCCESS',
                'result' => $comment
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
            $comment = Comments::findOrFail($id);
            return response()->json([
                'status' => 'SUCCESS',
                'result' => $comment
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
            'post_id' => 'required|exists:posts,id',
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $comment = Comments::findOrFail($id);
            $comment->update($request->all());
            return response()->json([
                'status' => 'SUCCESS',
                'result' => $comment
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
            $comment = Comments::findOrFail($id);

            try {
                $comment->delete();
                return response()->json([
                    'status' => 'SUCCESS'
                ], 200);
            } catch (QueryException $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ], 500);
            } 
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Comment not found'
            ], 404);
        }
    }
}
