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
        
        // Memuat relasi data agar frontend bisa menampilkan detail lengkap
        $query = Pengajuan::with(['program', 'user', 'operator', 'validator']);

        // JIKA ADMIN KECAMATAN: Filter data agar hanya memuat inputan dari kecamatan miliknya
        if ($userLogin->role === 'admin') {
            $query->where('operator_id', $userLogin->admin_id)
                  ->orWhereHas('operator', function($q) use ($userLogin) {
                      $q->where('kecamatan_id', $userLogin->kecamatan_id);
                  });
        }

        // JIKA SUPER ADMIN: Tampilkan semua data + Aktifkan Fitur Filter & History Tahunan
        if ($userLogin->role === 'super admin') {
            // Filter Berdasarkan Status Validasi (pending / disetujui / ditolak)
            if ($request->has('status')) {
                $query->where('status_pengajuan', $request->status);
            }
            // Fitur History Tahunan (Mencari lewat tahun anggaran di tabel program)
            if ($request->has('tahun')) {
                $query->whereHas('program', function($q) use ($request) {
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
            return response()->json(['message' => 'Akses ditolak. Hanya Admin Kecamatan yang dapat menginput pengajuan.'], 403);
        }

        $request->validate([
            'program_id' => 'required|exists:program,program_id',
            'user_id'    => 'required|exists:data_user,user_id',
        ]);

        $pengajuan = Pengajuan::create([
            'program_id'       => $request->program_id,
            'user_id'          => $request->user_id,
            'operator_id'      => $admin->admin_id,      // Otomatis mencatat admin kecamatan yang login
            'status_pengajuan' => 'pending',            // Menggunakan nilai enum perubahan baru
        ]);

        return response()->json([
            'message' => 'Pengajuan berhasil ditambahkan dan berstatus pending.',
            'data'    => $pengajuan
        ], 201);
    }

    /**
     * Mengambil Detail Pengajuan Tunggal
     */
    public function show($id)
    {
        $pengajuan = Pengajuan::with(['program', 'user', 'operator', 'validator'])->find($id);
        
        if (!$pengajuan) {
            return response()->json(['message' => 'Data pengajuan tidak ditemukan'], 404);
        }
        
        return response()->json($pengajuan, 200);
    }

    /**
     * Fitur Validasi Pengajuan (MUTLAK HANYA UNTUK SUPER ADMIN)
     */
    public function validateSubmission(Request $request, $id)
    {
        $superAdmin = $request->user();
        
        if ($superAdmin->role !== 'super admin') {
            return response()->json(['message' => 'Akses ditolak. Hanya Super Admin yang berhak melakukan validasi.'], 403);
        }

        $request->validate([
            'status_pengajuan'  => 'required|in:disetujui,ditolak',
            'catatan_pengajuan' => 'nullable|string',
        ]);

        $pengajuan = Pengajuan::find($id);
        if (!$pengajuan) {
            return response()->json(['message' => 'Data pengajuan tidak ditemukan'], 404);
        }

        $pengajuan->update([
            'status_pengajuan'  => $request->status_pengajuan,
            'catatan_pengajuan' => $request->catatan_pengajuan,
            'admin_id'          => $superAdmin->admin_id, // Mencatat Super Admin sebagai validator
            'tanggal_pengajuan' => now(),                 // Mencatat tanggal eksekusi validasi
        ]);

        return response()->json([
            'message' => 'Status pengajuan berhasil diperbarui oleh Super Admin.',
            'data'    => $pengajuan
        ], 200);
    }
}