<?php

namespace App\Http\Controllers;

use App\Models\Pindah;
use Illuminate\Http\Request;

class PindahController extends Controller
{
    // GET /api/pindah
    public function index(Request $request)
{
    $request->validate([
        'q' => ['nullable','string'],
        'start_date' => ['nullable','date_format:Y-m-d'],
        'end_date' => ['nullable','date_format:Y-m-d'],
        'sort' => ['nullable','in:newest,oldest'],
        'per_page' => ['nullable','integer','min:1','max:200'],
    ]);

    $q   = (string) $request->query('q', '');
    $dir = $request->query('sort', 'newest') === 'oldest' ? 'asc' : 'desc';
    $pp  = (int) $request->query('per_page', 10);

    $query = \App\Models\Pindah::query();

    if ($q !== '') {
        $query->where(function ($w) use ($q) {
            $w->where('nik','like',"%{$q}%")
              ->orWhere('nama_lengkap','like',"%{$q}%")
              ->orWhere('nomor_kk','like',"%{$q}%")
              ->orWhere('nomor_pindah','like',"%{$q}%");
        });
    }

    // filter tanggal sesuai entitas (pindah/kematian)
    if ($request->start_date) $query->whereDate('tanggal_pindah','>=',$request->start_date);
    if ($request->end_date)   $query->whereDate('tanggal_pindah','<=',$request->end_date);

    // ⬇️ urut stabil: non-NULL dulu, lalu created_at, lalu id
    $query->reorder()
          ->orderByRaw('CASE WHEN created_at IS NULL THEN 1 ELSE 0 END ASC')
          ->orderBy('created_at', $dir)
          ->orderBy('id', $dir);

    return $query->paginate($pp)->appends($request->query());
}

    // POST /api/pindah
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik'            => 'required|string|max:16',
            'nama_lengkap'   => 'required|string|max:255',
            'nomor_kk'       => 'required|string|max:16', // ✅ perbaikan nama kolom
            'nomor_pindah'   => 'required|string|max:50',
            'tanggal_pindah' => 'required|date',
        ]);

        $data = Pindah::create($validated);
        return response()->json($data, 201);
    }

    // GET /api/pindah/{id}
    public function show($id)
    {
        $data = Pindah::find($id);
        if (!$data) return response()->json(['message' => 'Data not found'], 404);
        return response()->json($data);
    }

    // PUT/PATCH /api/pindah/{id}
    public function update(Request $request, $id)
    {
        $data = Pindah::find($id);
        if (!$data) return response()->json(['message' => 'Data not found'], 404);

        $validated = $request->validate([
            'nik'            => 'sometimes|string|max:20',
            'nama_lengkap'   => 'sometimes|string|max:255',
            'nomor_kk'       => 'sometimes|string|max:20', // ✅ konsisten
            'nomor_pindah'   => 'sometimes|string|max:50',
            'tanggal_pindah' => 'sometimes|date',
        ]);

        $data->update($validated);
        return response()->json($data);
    }

    // DELETE /api/pindah/{id}
    public function destroy($id)
    {
        $data = Pindah::find($id);
        if (!$data) return response()->json(['message' => 'Data not found'], 404);

        $data->delete();
        return response()->noContent(); // 204
    }
}
