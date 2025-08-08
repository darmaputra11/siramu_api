<?php

namespace App\Http\Controllers;

use App\Models\Pindah;
use Illuminate\Http\Request;

class PindahController extends Controller
{
    // GET /api/pindah
    public function index()
    {
        return response()->json(Pindah::all());
    }

    // POST /api/pindah
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'nama_lengkap' => 'required',
            'no_kk' => 'required',
            'nomor_pindah' => 'required',
            'tanggal_pindah' => 'required|date',
        ]);

        $data = Pindah::create($request->all());

        return response()->json($data, 201);
    }

    // GET /api/pindah/{id}
    public function show($id)
    {
        $data = Pindah::find($id);
        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json($data);
    }

    // PUT/PATCH /api/pindah/{id}
    public function update(Request $request, $id)
    {
        $data = Pindah::find($id);
        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $data->update($request->all());

        return response()->json($data);
    }

    // DELETE /api/pindah/{id}
    public function destroy($id)
    {
        $data = Pindah::find($id);
        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $data->delete();

        return response()->json(['message' => 'Data deleted']);
    }
}
