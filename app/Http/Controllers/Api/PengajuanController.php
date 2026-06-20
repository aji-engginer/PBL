<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    /**
     * Tampilan Utama (Sharing Data untuk Admin & Super Admin)
     */
    public function index(Request $request)
    {
        $userLogin = $request->user();

        $query = Pengajuan::with(['program', 'user', 'operator', 'validator']);

        // JIKA ADMIN KECAMATAN: Filter data agar hanya memuat inputan dari kecamatan miliknya
        if ($userLogin->role === 'admin') {
            $query->where(function ($q) use ($userLogin) {
                $q->where('operator_id', $userLogin->admin_id)
                  ->orWhereHas('operator', function ($q2) use ($userLogin) {
                      $q2->where('kecamatan_id', $userLogin->kecamatan_id);
                  });
            });
        }

        // JIKA SUPER ADMIN: Tampilkan semua data + Aktifkan Fitur Filter & History Tahunan
        if ($userLogin->role === 'super admin') {
            if ($request->has('status')) {
                $query->where('status_pengajuan', $request->status);
            }
            if ($request->has('tahun')) {
                $query->whereHas('program', function ($q) use ($request) {
                    $q->where('tahun', $request->tahun);
                });
            }
        }

        return response()->json($query->latest()->get(), 200);
    }

    /**
     * Input Data Pengajuan (Hanya bisa dilakukan oleh Admin Kecamatan)
     */
    public function store(Request $request)
    {
        $admin = $request->user();

        if ($admin->role !== 'admin') {
            return response()->json([
                'message' => 'Akses ditolak. Hanya Admin Kecamatan yang dapat menginput pengajuan.'
            ], 403);
        }

        $request->validate([
            'program_id' => 'required|exists:program,program_id',
            'user_id'    => 'required|exists:data_user,user_id',
        ]);

        $pengajuan = Pengajuan::create([
            'program_id'        => $request->program_id,
            'user_id'           => $request->user_id,
            'operator_id'       => $admin->admin_id,
            'status_pengajuan'  => 'pending',
            'tanggal_pengajuan' => now(),
        ]);

        return response()->json([
            'message' => 'Pengajuan berhasil ditambahkan dan berstatus pending.',
            'data'    => $pengajuan->load(['program', 'user', 'operator'])
        ], 201);
    }

    /**
     * Mengambil Detail Pengajuan Tunggal
     */
    public function show($id)
    {
        $pengajuan = Pengajuan::with(['program', 'user', 'operator', 'validator'])->find($id);

        if (!$pengajuan) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($pengajuan, 200);
    }

    /**
     * Update Pengajuan (Hanya operator yang menginput, dan hanya saat status masih pending)
     */
    public function update(Request $request, $id)
    {
        $admin = $request->user();

        $pengajuan = Pengajuan::find($id);
        if (!$pengajuan) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        if ($pengajuan->operator_id !== $admin->admin_id) {
            return response()->json([
                'message' => 'Akses ditolak. Anda bukan operator pengajuan ini.'
            ], 403);
        }

        if ($pengajuan->status_pengajuan !== 'pending') {
            return response()->json([
                'message' => 'Pengajuan tidak dapat diubah karena sudah diproses.'
            ], 422);
        }

        $request->validate([
            'program_id' => 'sometimes|exists:program,program_id',
            'user_id'    => 'sometimes|exists:data_user,user_id',
        ]);

        $pengajuan->update($request->only(['program_id', 'user_id']));

        return response()->json([
            'message' => 'Pengajuan berhasil diperbarui.',
            'data'    => $pengajuan->load(['program', 'user', 'operator'])
        ], 200);
    }

    /**
     * Hapus Pengajuan (Hanya saat status masih pending)
     */
    public function destroy($id)
    {
        $pengajuan = Pengajuan::find($id);

        if (!$pengajuan) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        if ($pengajuan->status_pengajuan !== 'pending') {
            return response()->json([
                'message' => 'Pengajuan tidak dapat dihapus karena sudah diproses.'
            ], 422);
        }

        $pengajuan->delete();

        return response()->json(['message' => 'Pengajuan berhasil dihapus.'], 200);
    }

    /**
     * Fitur Validasi Pengajuan (MUTLAK HANYA UNTUK SUPER ADMIN)
     */
    public function validate(Request $request, $id)
    {
        $superAdmin = $request->user();

        if ($superAdmin->role !== 'super admin') {
            return response()->json([
                'message' => 'Akses ditolak. Hanya Super Admin yang berhak melakukan validasi.'
            ], 403);
        }

        $pengajuan = Pengajuan::find($id);
        if (!$pengajuan) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        if ($pengajuan->status_pengajuan !== 'pending') {
            return response()->json([
                'message' => 'Pengajuan ini sudah pernah divalidasi sebelumnya.'
            ], 422);
        }

        $request->validate([
            'status_pengajuan'  => 'required|in:disetujui,ditolak',
            'catatan_pengajuan' => 'nullable|string',
        ]);

        $pengajuan->update([
            'status_pengajuan'  => $request->status_pengajuan,
            'catatan_pengajuan' => $request->catatan_pengajuan,
            'admin_id'          => $superAdmin->admin_id,
            'tanggal_validasi'  => now(),
        ]);

        return response()->json([
            'message' => 'Pengajuan berhasil divalidasi.',
            'data'    => $pengajuan->load(['program', 'user', 'operator', 'validator'])
        ], 200);
    }
}