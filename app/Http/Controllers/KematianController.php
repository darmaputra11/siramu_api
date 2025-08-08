<?php

namespace App\Http\Controllers;

use App\Models\Kematian;
use Illuminate\Http\Request;

class KematianController extends Controller
{
    public function index()
    {
        return response()->json(Kematian::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'nama_lengkap' => 'required',
            'tanggal_kematian' => 'required|date',
            'nomor_akta' => 'required',
        ]);

        $data = Kematian::create($request->all());

        return response()->json($data, 201);
    }

    public function show($id)
    {
        $data = Kematian::find($id);
        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $data = Kematian::find($id);
        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $data->update($request->all());

        return response()->json($data);
    }

    public function destroy($id)
    {
        $data = Kematian::find($id);
        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $data->delete();

        return response()->json(['message' => 'Data deleted']);
    }
}
