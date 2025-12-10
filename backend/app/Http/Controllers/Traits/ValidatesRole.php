<?php

namespace App\Http\Controllers\Traits;

use App\Constants\UserRoles;
use Illuminate\Http\Request;

trait ValidatesRole
{
    /**
     * Validate user has specific role
     * 
     * @param Request $request
     * @param string $requiredRole
     * @return \Illuminate\Http\JsonResponse|null Returns error response if validation fails, null if passes
     */
    protected function validateRole(Request $request, string $requiredRole): ?\Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->role !== $requiredRole) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return null;
    }

    /**
     * Validate user has mahasiswa role
     */
    protected function validateMahasiswa(Request $request): ?\Illuminate\Http\JsonResponse
    {
        return $this->validateRole($request, UserRoles::MAHASISWA);
    }

    /**
     * Validate user has perusahaan role
     */
    protected function validatePerusahaan(Request $request): ?\Illuminate\Http\JsonResponse
    {
        return $this->validateRole($request, UserRoles::PERUSAHAAN);
    }

    /**
     * Validate user has admin role
     */
    protected function validateAdmin(Request $request): ?\Illuminate\Http\JsonResponse
    {
        return $this->validateRole($request, UserRoles::ADMIN);
    }

    /**
     * Get mahasiswa profile or return error
     */
    protected function getMahasiswaOrFail(Request $request): array
    {
        $user = $request->user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return [
                'error' => response()->json(['message' => 'Profil mahasiswa tidak ditemukan'], 404),
                'mahasiswa' => null
            ];
        }

        return [
            'error' => null,
            'mahasiswa' => $mahasiswa
        ];
    }

    /**
     * Get perusahaan profile or return error
     */
    protected function getPerusahaanOrFail(Request $request): array
    {
        $user = $request->user();
        $perusahaan = $user->perusahaan;

        if (!$perusahaan) {
            return [
                'error' => response()->json(['message' => 'Profil perusahaan tidak ditemukan'], 404),
                'perusahaan' => null
            ];
        }

        return [
            'error' => null,
            'perusahaan' => $perusahaan
        ];
    }
}

