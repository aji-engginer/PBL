<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // GET /api/admin
    // Lihat semua admin kecamatan + filter opsional by kecamatan_id
    public function index(Request $request)
    {
        $query = Admin::with('kecamatan')
            ->where('role', 'admin') // hanya tampilkan admin kecamatan, bukan super admin
            ->withCount('pengajuan'); // hitung jumlah pengajuan yang pernah diinput

        if ($request->has('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        return response()->json($query->get(), 200);
    }

    // POST /api/admin
    // Buat akun admin kecamatan baru
    public function store(Request $request)
    {
        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatan,kecamatan_id',
            'nama'         => 'required|string|max:255',
            'email'        => 'required|email|unique:admin,email',
            'password'     => 'required|string|min:8',
        ]);

        $admin = Admin::create([
            'kecamatan_id' => $request->kecamatan_id,
            'nama'         => $request->nama,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'role'         => 'admin', // selalu admin kecamatan, tidak bisa buat super admin dari sini
        ]);

        return response()->json([
            'message' => 'Akun admin kecamatan berhasil dibuat.',
            'data'    => $admin->load('kecamatan')
        ], 201);
    }

    // GET /api/admin/{id}
    // Detail satu admin + jumlah pengajuan yang pernah diinput
    public function show($id)
    {
        $admin = Admin::with('kecamatan')
            ->withCount('pengajuan')
            ->where('role', 'admin')
            ->find($id);

        if (!$admin) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($admin, 200);
    }

    // PUT /api/admin/{id}
    // Edit data admin kecamatan (nama, email, kecamatan)
    public function update(Request $request, $id)
    {
        $admin = Admin::where('role', 'admin')->find($id);

        if (!$admin) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatan,kecamatan_id',
            'nama'         => 'required|string|max:255',
            'email'        => 'required|email|unique:admin,email,' . $admin->admin_id . ',admin_id',
        ]);

        $admin->update($request->only(['kecamatan_id', 'nama', 'email']));

        return response()->json([
            'message' => 'Data admin berhasil diperbarui.',
            'data'    => $admin->load('kecamatan')
        ], 200);
    }

    // DELETE /api/admin/{id}
    // Hapus akun admin kecamatan
    public function destroy($id)
    {
        $admin = Admin::where('role', 'admin')->find($id);

        if (!$admin) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $admin->delete();

        return response()->json([
            'message' => 'Akun admin berhasil dihapus.'
        ], 200);
    }

    // PATCH /api/admin/{id}/reset-password
    // Reset password admin kecamatan oleh super admin
    public function resetPassword(Request $request, $id)
    {
        $admin = Admin::where('role', 'admin')->find($id);

        if (!$admin) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed', // butuh password_confirmation
        ]);

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Password admin berhasil direset.'
        ], 200);
    }
}