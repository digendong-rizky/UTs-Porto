<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Portofolio extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'nama',
        'bidang', // backend, frontend, fullstack, QATester
        'education', // JSON atau text
        'language', // JSON atau text
        'deskripsi', // Deskripsi portofolio
        'public_link',
        'pdf_path',
        'is_public',
        'custom_css',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($portofolio) {
            if (empty($portofolio->public_link)) {
                $portofolio->public_link = Str::random(32);
            }
        });
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    /**
     * Base query for public portfolios (shared)
     * @param string|null $bidang Filter by bidang (backend, frontend, fullstack, QATester)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function publicQuery($bidang = null)
    {
        $query = static::with([
                'mahasiswa:id,user_id,nim,jurusan,fakultas,universitas',
                'mahasiswa.user:id,name,email',
                'skills:id,portofolio_id,nama,level'
            ])
            ->select('id', 'mahasiswa_id', 'nama', 'bidang', 'deskripsi', 'public_link', 'is_public', 'created_at', 'updated_at')
            ->where('is_public', true);

        if ($bidang) {
            $query->where('bidang', $bidang);
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get all public portfolios with relationships (optimized)
     * @param string|null $bidang Filter by bidang (backend, frontend, fullstack, QATester)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPublicPortfolios($bidang = null)
    {
        return static::publicQuery($bidang)->get();
    }

    /**
     * Get all public portfolios by mahasiswa ID
     * @param int $mahasiswaId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPublicPortfoliosByMahasiswa($mahasiswaId)
    {
        return static::with([
            'mahasiswa.user:id,name,email',
            'skills:id,portofolio_id,nama',
            'projects',
            'certificates',
            'experiences'
        ])
        ->where('mahasiswa_id', $mahasiswaId)
        ->where('is_public', true)
        ->get();
    }

    /**
     * Find portfolio by public link (optimized)
     * @param string $publicLink
     * @return Portofolio|null
     */
    public static function findByPublicLink($publicLink)
    {
        return static::with([
                'mahasiswa:id,user_id,nim,jurusan,fakultas,universitas,deskripsi_diri',
                'mahasiswa.user:id,name,email',
                'skills:id,portofolio_id,nama,level',
                'projects:id,portofolio_id,judul,deskripsi,link,gambar,teknologi,tanggal_mulai,tanggal_selesai,urutan',
                'certificates:id,portofolio_id,nama,penerbit,tanggal_terbit,tanggal_kadaluarsa,urutan',
                'experiences:id,portofolio_id,judul,perusahaan,deskripsi,tanggal_mulai,tanggal_selesai,urutan'
            ])
            ->select('id', 'mahasiswa_id', 'nama', 'bidang', 'education', 'language', 'deskripsi', 'public_link', 'is_public', 'custom_css', 'created_at', 'updated_at')
            ->where('public_link', $publicLink)
            ->where('is_public', true)
            ->first();
    }

    /**
     * Scope untuk filter by bidang
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $bidang
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByBidang($query, $bidang = null)
    {
        if ($bidang) {
            return $query->where('bidang', $bidang);
        }
        return $query;
    }

    /**
     * Get portfolios by user ID (optimized with eager loading)
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByUserId($userId)
    {
        return static::whereHas('mahasiswa', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->select('id', 'mahasiswa_id', 'nama', 'bidang', 'deskripsi', 'public_link', 'is_public', 'created_at', 'updated_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}

