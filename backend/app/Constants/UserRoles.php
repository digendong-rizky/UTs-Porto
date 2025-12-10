<?php

namespace App\Constants;

class UserRoles
{
    const MAHASISWA = 'mahasiswa';
    const PERUSAHAAN = 'perusahaan';
    const ADMIN = 'admin';

    /**
     * Get all available roles
     */
    public static function all(): array
    {
        return [
            self::MAHASISWA,
            self::PERUSAHAAN,
            self::ADMIN,
        ];
    }

    /**
     * Check if role is valid
     */
    public static function isValid(string $role): bool
    {
        return in_array($role, self::all());
    }
}

