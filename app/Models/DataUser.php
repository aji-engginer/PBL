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
        'bank',
        'kategori',
    ];

    // Field URL file — otomatis ikut di response JSON
    protected $appends = [
        'foto_ktp_url',
        'foto_kk_url',
        'surat_putusan_url',
        'surat_keterangan_pimpinan_pondok_url',
        'surat_keterangan_guru_mengaji_url',
        'surat_keterangan_kecamatan_url',
    ];

    public function pondok()
    {
        return $this->belongsTo(Pondok::class, 'pondok_id', 'pondok_id');
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'user_id');
    }

    // ── Accessor URL File ──────────────────────────────────────

    public function getFotoKtpUrlAttribute()
    {
        return $this->foto_ktp ? asset('storage/' . $this->foto_ktp) : null;
    }

    public function getFotoKkUrlAttribute()
    {
        return $this->foto_kk ? asset('storage/' . $this->foto_kk) : null;
    }

    public function getSuratPutusanUrlAttribute()
    {
        return $this->surat_putusan ? asset('storage/' . $this->surat_putusan) : null;
    }

    public function getSuratKeteranganPimpinanPondokUrlAttribute()
    {
        return $this->surat_keterangan_pimpinan_pondok
            ? asset('storage/' . $this->surat_keterangan_pimpinan_pondok)
            : null;
    }

    public function getSuratKeteranganGuruMengajiUrlAttribute()
    {
        return $this->surat_keterangan_guru_mengaji
            ? asset('storage/' . $this->surat_keterangan_guru_mengaji)
            : null;
    }

    public function getSuratKeteranganKecamatanUrlAttribute()
    {
        return $this->surat_keterangan_kecamatan
            ? asset('storage/' . $this->surat_keterangan_kecamatan)
            : null;
    }
}