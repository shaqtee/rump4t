<?php

namespace Modules\Pemilu\App\Http\Controllers;

use DB;
use Modules\Pemilu\App\Http\Requests\PemiluRequest;
use App\Http\Controllers\Controller;
use App\Models\Pemilu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class PemiluController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Pemilu::query();
            $page = $request->input('size', 10);
            
            if ($request->has('size')) {
                $pemilus = $query
                    ->filter($request)
                    ->paginate($page)
                    ->appends($request->all());
            }else{
                $pemilus = $query->filter($request)->get();
            }

            return response()->json([
                'status' => 'success',
                'data' => $pemilus
            ]);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'status' => 'failed',
                'message' => 'Terjadi kesalahan saat mengambil data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pemilu::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PemiluRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
    
            $data = $request->validated();
            $data['created_by'] = auth()->id();
                
            $pemilu = Pemilu::create($data);
    
            DB::commit();
    
            return response()->json([
                "status" => "success",
                "data" => $pemilu
            ], 201);

        } catch (\Exception $e) {
            report($e);
            DB::rollback();

            return response()->json([
                "status" => "failed",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('pemilu::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('pemilu::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pemilu $pemilu): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'start_date' => 'sometimes|required|date',
                'end_date' => 'sometimes|required|date',
                'is_active' => 'sometimes|required|bool',
            ]);

            $pemilu->update($data);
            DB::commit();

            return response()->json([
                "status" => "success",
                "data" => $pemilu
            ], 201);
        } catch (\Exception $e) {
            report($e);
            DB::rollback();

            return response()->json([
                "status" => "failed",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $pemilu = Pemilu::findOrFail($id);
            $pemilu->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pemilu berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            report($e);
            DB::rollback();

            return response()->json([
                'status' => 'failed',
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
