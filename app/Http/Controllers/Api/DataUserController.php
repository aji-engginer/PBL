<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataUserController extends Controller
{
    // GET /api/data-user
    public function index()
    {
        $users = DataUser::with(['pondok', 'jenisUser'])->get();
        return response()->json($users, 200);
    }

    // POST /api/data-user
    public function store(Request $request)
    {
        $request->validate([
            'pondok_id'       => 'nullable|exists:pondok,pondok_id',
            'jenis_user_id'   => 'nullable|exists:jenis_user,jenis_user_id',
            'nama_lengkap'    => 'required|string|max:255',
            'nik'             => 'required|string|size:16|unique:data_user,nik',
            'tempat_lahir'    => 'required|string|max:255',
            'alamat'          => 'required|string',
            'nomor_rekening'  => 'required|string|max:255',
            'foto_ktp'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_kk'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_putusan'                       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_keterangan_pimpinan_pondok'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_keterangan_guru_mengaji'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_keterangan_kecamatan'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $request->only([
            'pondok_id', 'jenis_user_id', 'nama_lengkap', 'nik',
            'tempat_lahir', 'alamat', 'nomor_rekening'
        ]);

        $fileFields = [
            'foto_ktp', 'foto_kk', 'surat_putusan',
            'surat_keterangan_pimpinan_pondok',
            'surat_keterangan_guru_mengaji',
            'surat_keterangan_kecamatan'
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store("uploads/{$field}", 'public');
            }
        }

        $user = DataUser::create($data);
        return response()->json(['message' => 'Data user berhasil ditambahkan', 'data' => $user], 201);
    }

    // GET /api/data-user/{id}
    public function show($id)
    {
        $user = DataUser::with(['pondok', 'jenisUser'])->find($id);

        if (!$user) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($user, 200);
    }

    // PUT /api/data-user/{id}
    public function update(Request $request, $id)
    {
        $user = DataUser::find($id);

        if (!$user) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $request->validate([
            'pondok_id'       => 'nullable|exists:pondok,pondok_id',
            'jenis_user_id'   => 'nullable|exists:jenis_user,jenis_user_id',
            'nama_lengkap'    => 'required|string|max:255',
            'nik'             => 'required|string|size:16|unique:data_user,nik,' . $user->user_id . ',user_id',
            'tempat_lahir'    => 'required|string|max:255',
            'alamat'          => 'required|string',
            'nomor_rekening'  => 'required|string|max:255',
            'foto_ktp'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_kk'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_putusan'                       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_keterangan_pimpinan_pondok'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_keterangan_guru_mengaji'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_keterangan_kecamatan'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $request->only([
            'pondok_id', 'jenis_user_id', 'nama_lengkap', 'nik',
            'tempat_lahir', 'alamat', 'nomor_rekening'
        ]);

        $fileFields = [
            'foto_ktp', 'foto_kk', 'surat_putusan',
            'surat_keterangan_pimpinan_pondok',
            'surat_keterangan_guru_mengaji',
            'surat_keterangan_kecamatan'
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                if ($user->$field) {
                    Storage::disk('public')->delete($user->$field);
                }
                $data[$field] = $request->file($field)->store("uploads/{$field}", 'public');
            }
        }

        $user->update($data);
        return response()->json(['message' => 'Data user berhasil diperbarui', 'data' => $user], 200);
    }

    // DELETE /api/data-user/{id}
    public function destroy($id)
    {
        $user = DataUser::find($id);

        if (!$user) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $fileFields = [
            'foto_ktp', 'foto_kk', 'surat_putusan',
            'surat_keterangan_pimpinan_pondok',
            'surat_keterangan_guru_mengaji',
            'surat_keterangan_kecamatan'
        ];

        foreach ($fileFields as $field) {
            if ($user->$field) {
                Storage::disk('public')->delete($user->$field);
            }
        }

        $user->delete();
        return response()->json(['message' => 'Data user berhasil dihapus'], 200);
    }
}