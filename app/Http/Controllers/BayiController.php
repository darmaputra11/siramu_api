<?php

namespace App\Http\Controllers;

use App\Models\Bayi;
use Illuminate\Http\Request;

class BayiController extends Controller
{
    /**
     * Query params (opsional):
     * - q          : keyword (nama / nik / no_entitas / nama_ibu_kandung)
     * - per_page   : default 10
     * - start_date : filter tgl_lahir_bayi >= (YYYY-MM-DD)
     * - end_date   : filter tgl_lahir_bayi <= (YYYY-MM-DD)
     * - sort_by    : created_at|tgl_lahir_bayi|tgl_lahir_ibu_kandung (default: created_at)
     * - sort_dir   : asc|desc (default: desc)
     */
    public function index(Request $request)
    {
        $q       = (string) $request->query('q', '');
        $perPage = (int) $request->query('per_page', 10);
        $start   = $request->query('start_date');
        $end     = $request->query('end_date');

        $sortBy  = $request->query('sort_by', 'created_at');
        $sortDir = strtolower($request->query('sort_dir', 'desc')) === 'desc' ? 'desc' : 'asc';

        $allowedSort = ['created_at', 'tgl_lahir_bayi', 'tgl_lahir_ibu_kandung'];
        if (!in_array($sortBy, $allowedSort, true)) {
            $sortBy = 'created_at';
        }

        $rows = Bayi::query()
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where(function ($s) use ($q) {
                    $s->where('nama', 'like', "%$q%")
                      ->orWhere('nik', 'like', "%$q%")
                      ->orWhere('no_entitas', 'like', "%$q%")
                      ->orWhere('nama_ibu_kandung', 'like', "%$q%");
                });
            })
            ->when($start, fn ($qb) => $qb->whereDate('tgl_lahir_bayi', '>=', $start))
            ->when($end,   fn ($qb) => $qb->whereDate('tgl_lahir_bayi', '<=', $end))
            ->orderBy($sortBy, $sortDir)
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json($rows);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'no_entitas'           => ['required', 'string', 'max:64'],
            'nik'                  => ['required', 'string', 'max:16'],
            'nama'                 => ['required', 'string', 'max:150'],
            'hub_keluarga'         => ['nullable', 'string', 'max:100'],
            'tgl_lahir_bayi'       => ['required', 'date'],
            'nama_ibu_kandung'     => ['required', 'string', 'max:150'],
            'tgl_lahir_ibu_kandung'=> ['required', 'date'],
        ]);

        $row = Bayi::create($data);

        return response()->json($row, 201);
    }

    public function show($id)
    {
        $row = Bayi::find($id);
        if (!$row) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json($row);
    }

    public function update(Request $request, $id)
    {
        $row = Bayi::find($id);
        if (!$row) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $data = $request->validate([
            'no_entitas'           => ['sometimes', 'string', 'max:64'],
            'nik'                  => ['sometimes', 'string', 'max:16'],
            'nama'                 => ['sometimes', 'string', 'max:150'],
            'hub_keluarga'         => ['sometimes', 'nullable', 'string', 'max:100'],
            'tgl_lahir_bayi'       => ['sometimes', 'date'],
            'nama_ibu_kandung'     => ['sometimes', 'string', 'max:150'],
            'tgl_lahir_ibu_kandung'=> ['sometimes', 'date'],
        ]);

        $row->update($data);

        return response()->json($row);
    }

    public function destroy($id)
    {
        $row = Bayi::find($id);
        if (!$row) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $row->delete();

        return response()->json(['message' => 'Data deleted']);
    }
}
