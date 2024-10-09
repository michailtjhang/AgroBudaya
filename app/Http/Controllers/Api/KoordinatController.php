<?php

namespace App\Http\Controllers\Api;

use App\Models\Koordinat;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class KoordinatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $koordinat = Koordinat::all();
        return response()->json($koordinat, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_budaya' => 'required|string',
            'kode_koordinat' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $koordinat = Koordinat::create([
                'nama_budaya' => $request->nama_budaya,
                'kode_koordinat' => $request->kode_koordinat,
            ]);

            return response()->json([
                'status' => 'SUCCESS',
                'result' => $koordinat
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed to create koordinat, please try again later.'
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
            'kode_koordinat' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $koordinat = Koordinat::findOrFail($id);
            $koordinat->update($request->all());

            return response()->json([
                'status' => 'SUCCESS',
                'result' => $koordinat
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed to update koordinat, please try again later.'
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
