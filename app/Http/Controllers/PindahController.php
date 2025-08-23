<?php

namespace App\Http\Controllers;

use App\Models\Pindah;
use Illuminate\Http\Request;

class PindahController extends Controller
{
    // GET /api/pindah
    public function index(Request $request)
    {
        $q       = (string) $request->query('q', '');
        $perPage = (int) $request->query('per_page', 10);
        $start   = $request->query('start_date'); // yyyy-mm-dd
        $end     = $request->query('end_date');   // yyyy-mm-dd
        $sort    = $request->query('sort', 'oldest'); // oldest|newest

        $rows = Pindah::query()
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where('nama_lengkap', 'like', "%$q%")
                   ->orWhere('nik', 'like', "%$q%")
                   ->orWhere('nomor_kk', 'like', "%$q%")
                   ->orWhere('nomor_pindah', 'like', "%$q%");
            })
            ->when($start, fn($qb) => $qb->whereDate('tanggal_pindah', '>=', $start))
            ->when($end,   fn($qb) => $qb->whereDate('tanggal_pindah', '<=', $end))
            ->orderBy('tanggal_pindah', $sort === 'newest' ? 'desc' : 'asc')
            ->paginate($perPage);

        return response()->json($rows);
    }

    // POST /api/pindah
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik'            => 'required|string|size:16',
            'nama_lengkap'   => 'required|string|max:255',
            'nomor_kk'       => 'required|string|size:16', // ✅ perbaikan nama kolom
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
            'nik'            => 'sometimes|string|size:16',
            'nama_lengkap'   => 'sometimes|string|max:255',
            'nomor_kk'       => 'sometimes|string|size:16', // ✅ konsisten
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
