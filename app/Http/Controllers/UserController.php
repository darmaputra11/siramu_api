<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /** Role yang diizinkan */
    private const ALLOWED_ROLES = ['admin', 'viewer', 'dinsos', 'dinkes', 'bpjs'];

    /**
     * List user (paginator default Laravel) + search & per_page.
     * GET /api/users?q=foo&page=1&per_page=10
     */
    public function index(Request $request)
{
    $q       = (string) $request->query('q', '');
    $perPage = (int) $request->query('per_page', 10);

    $users = User::query()
        ->when($q !== '', function ($qb) use ($q) {
            $qb->where(function ($s) use ($q) {
                $s->where('name', 'like', "%{$q}%")
                  ->orWhere('email','like', "%{$q}%")
                  ->orWhere('role', 'like', "%{$q}%");
            });
        })
        ->orderBy('created_at', 'asc') // ✅ oldest duluan
        ->paginate($perPage);

    return response()->json($users);
}

    /**
     * Create user baru.
     * Wajib kirim password_confirmation (validated via confirmed).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                  => ['required','string','max:255','unique:users,name'],
            'email'                 => ['required','email','max:255','unique:users,email'],
            'password'              => ['required','string','min:6','confirmed'], // butuh password_confirmation
            'role'                  => ['required', Rule::in(self::ALLOWED_ROLES)],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
        ], 201);
    }

    /**
     * Detail user.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update user (parsial). Jika kirim password, wajib minimal 6 dan boleh pakai confirmed.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'                  => ['sometimes','string','max:255', Rule::unique('users','name')->ignore($id)],
            'email'                 => ['sometimes','email','max:255',  Rule::unique('users','email')->ignore($id)],
            'role'                  => ['sometimes', Rule::in(self::ALLOWED_ROLES)],
            // jika password dikirim, validasi. Jika mau wajib confirmed saat dikirim, aktifkan 'confirmed'
            'password'              => ['sometimes','string','min:6','confirmed'],
        ]);

        $data = collect($validated)->only(['name','email','role'])->toArray();

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
        ]);
    }

    /**
     * Hapus user (tidak boleh hapus diri sendiri).
     */
    public function destroy($id)
    {
        $auth = request()->user();

        if ((int)$id === (int)$auth->id) {
            return response()->json([
                'message' => 'Tidak bisa menghapus akun sendiri.'
            ], 422);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->noContent(); // 204
    }
}
