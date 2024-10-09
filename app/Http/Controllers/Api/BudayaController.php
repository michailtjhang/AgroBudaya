<?php

namespace App\Http\Controllers\Api;

use App\Models\Budaya;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class BudayaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $budaya = Budaya::all();
        return response()->json($budaya, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_budaya' => 'required|string',
            'jam_operasional' => 'required|time',
            'biaya' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $budaya = Budaya::create([
                'nama_budaya' => $request->nama_budaya,
                'jam_operasional' => $request->jam_operasional,
                'biaya' => $request->biaya,
            ]);

            return response()->json([
                'status' => 'SUCCESS',
                'result' => $budaya
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed to create budaya, please try again later.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $validator = Validator::make($request->all(), [
            'nama_budaya' => 'required|string',
            'jam_operasional' => 'required',
            'biaya' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $budaya = Budaya::findOrFail($id);
            $budaya->update($request->all());

            return response()->json([
                'status' => 'SUCCESS',
                'result' => $budaya
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed to update budaya, please try again later.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
