<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataUser extends Model
{
    protected $table = 'data_user';
    protected $primaryKey = 'user_id';
    protected $hidden = ['password', 'remember_token'];

    protected $fillable = [
        'pondok_id',
        'jenis_user_id',
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'alamat',
        'foto_ktp',
        'foto_kk',
        'surat_putusan',
        'surat_keterangan_pimpinan_pondok',
        'surat_keterangan_guru_mengaji',
        'surat_keterangan_kecamatan',
        'nomor_rekening',
    ];

    public function pondok()
    {
        return $this->belongsTo(Pondok::class, 'pondok_id', 'pondok_id');
    }

    public function jenisUser()
    {
        return $this->belongsTo(JenisUser::class, 'jenis_user_id', 'jenis_user_id');
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'user_id');
    }
}