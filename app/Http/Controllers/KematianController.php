<?php

namespace App\Http\Controllers;

use App\Models\Kematian;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KematianController extends Controller
{
    /**
     * Query params (opsional):
     * - q                : keyword (nama_lengkap / nik / nomor_akta)
     * - per_page         : default 10
     * - start_date       : filter tanggal_kematian >= (YYYY-MM-DD)
     * - end_date         : filter tanggal_kematian <= (YYYY-MM-DD)
     * - akta_start_date  : filter tanggal_akta     >= (YYYY-MM-DD)
     * - akta_end_date    : filter tanggal_akta     <= (YYYY-MM-DD)
     * - sort_by          : created_at|tanggal_kematian|tanggal_akta  (default: created_at)
     * - sort_dir         : asc|desc (default: asc; jika sort_by=tanggal_akta biasanya pakai desc)
     */
    public function index(Request $request)
{
    $q           = (string) $request->query('q', '');
    $perPage     = (int) $request->query('per_page', 10);
    $start       = $request->query('start_date');
    $end         = $request->query('end_date');
    $aktaStart   = $request->query('akta_start_date');
    $aktaEnd     = $request->query('akta_end_date');

    // DEFAULT: terbaru duluan
    $sortBy  = $request->query('sort_by', 'created_at');               // default kolom
    $sortDir = strtolower($request->query('sort_dir', 'desc')) === 'desc' ? 'desc' : 'asc'; // default arah

    $allowedSort = ['created_at', 'tanggal_kematian', 'tanggal_akta'];
    if (!in_array($sortBy, $allowedSort, true)) {
        $sortBy = 'created_at';
    }

    $rows = Kematian::query()
        ->when($q !== '', function ($qb) use ($q) {
            $qb->where(function ($s) use ($q) {
                $s->where('nama_lengkap', 'like', "%$q%")
                  ->orWhere('nik', 'like', "%$q%")
                  ->orWhere('nomor_akta', 'like', "%$q%");
            });
        })
        ->when($start, fn ($qb) => $qb->whereDate('tanggal_kematian', '>=', $start))
        ->when($end,   fn ($qb) => $qb->whereDate('tanggal_kematian', '<=', $end))
        ->when($aktaStart, fn ($qb) => $qb->whereDate('tanggal_akta', '>=', $aktaStart))
        ->when($aktaEnd,   fn ($qb) => $qb->whereDate('tanggal_akta', '<=', $aktaEnd))
        ->orderBy($sortBy, $sortDir)       // default: created_at desc
        ->orderByDesc('id')                // tie-breaker biar stabil
        ->paginate($perPage);

    return response()->json($rows);
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'nik'               => ['required', 'string', 'max:16'],
            'nama_lengkap'      => ['required', 'string', 'max:150'],
            'tanggal_kematian'  => ['required', 'date'],
            'nomor_akta'        => ['required', 'string', 'max:64', 'unique:tb_kematian,nomor_akta'],
            // tanggal_akta opsional, tapi kalau diisi harus tanggal dan tidak lebih awal dari tanggal_kematian
            'tanggal_akta'      => ['required', 'date', 'after_or_equal:tanggal_kematian'],
        ]);

        // batasi field yang bisa diisi
        $data = collect($data)->only([
            'nik','nama_lengkap','tanggal_kematian','nomor_akta','tanggal_akta'
        ])->toArray();

        $row = Kematian::create($data);

        return response()->json($row, 201);
    }

    public function show($id)
    {
        $row = Kematian::find($id);
        if (!$row) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json($row);
    }

    public function update(Request $request, $id)
    {
        $row = Kematian::find($id);
        if (!$row) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $data = $request->validate([
            'nik'               => ['sometimes', 'string', 'max:16'],
            'nama_lengkap'      => ['sometimes', 'string', 'max:150'],
            'tanggal_kematian'  => ['sometimes', 'date'],
            'nomor_akta'        => ['sometimes', 'string', 'max:64', Rule::unique('tb_kematian', 'nomor_akta')->ignore($row->id)],
            // di update: cukup pastikan valid date (tanpa after_or_equal biar fleksibel ketika salah satu tidak dikirim)
            'tanggal_akta'      => ['sometimes', 'nullable', 'date'],
        ]);

        $row->update(collect($data)->only([
            'nik','nama_lengkap','tanggal_kematian','nomor_akta','tanggal_akta'
        ])->toArray());

        return response()->json($row);
    }

    public function destroy($id)
    {
        $row = Kematian::find($id);
        if (!$row) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $row->delete();

        return response()->json(['message' => 'Data deleted']);
    }
}
