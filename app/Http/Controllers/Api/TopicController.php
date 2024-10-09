<?php

namespace App\Http\Controllers\API;

use App\Models\Topics;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Topics::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $topic = Topics::create([
                'title' => $request->title,
                'category_id' => $request->category_id,
                'user_id' => $request->user_id,
            ]);

            return response()->json([
                'status' => 'SUCCESS',
                'result' => $topic
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
            $topic = Topics::findOrFail($id);
            return response()->json([
                'status' => 'SUCCESS',
                'result' => $topic
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
            'title' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $topic = Topics::findOrFail($id);
            $topic->update($request->all());
            return response()->json([
                'status' => 'SUCCESS',
                'result' => $topic
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
            $topic = Topics::findOrFail($id);
             
            try {
                $topic->delete();
                return response()->json([
                    'status' => 'SUCCESS',
                    'result' => $topic
                ], 200);
            } catch (QueryException $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ], 500);
            }
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Topic not found'
            ], 404);
        }
    }
}
