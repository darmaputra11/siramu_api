<?php

namespace App\Http\Controllers;

use App\Models\Kematian;
use Illuminate\Http\Request;

class KematianController extends Controller
{
    public function index(Request $request)
{
    $q       = (string) $request->query('q', '');
    $perPage = (int) $request->query('per_page', 10);
    $start   = $request->query('start_date');
    $end     = $request->query('end_date');
    $sort    = $request->query('sort', 'oldest'); // oldest|newest

    $rows = \App\Models\Kematian::query()
        ->when($q !== '', fn($qb) =>
            $qb->where('nama_lengkap','like',"%$q%")
               ->orWhere('nik','like',"%$q%")
               ->orWhere('nomor_akta','like',"%$q%")
        )
        ->when($start, fn($qb) => $qb->whereDate('tanggal_kematian','>=',$start))
        ->when($end,   fn($qb) => $qb->whereDate('tanggal_kematian','<=',$end))
        ->orderBy('tanggal_kematian', $sort === 'newest' ? 'desc' : 'asc')
        ->paginate($perPage);

    return response()->json($rows);
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
