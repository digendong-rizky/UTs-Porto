<?php

namespace App\Constants;

class PortfolioBidang
{
    const BACKEND = 'backend';
    const FRONTEND = 'frontend';
    const FULLSTACK = 'fullstack';
    const QA_TESTER = 'QATester';

    /**
     * Get all available bidang
     */
    public static function all(): array
    {
        return [
            self::BACKEND,
            self::FRONTEND,
            self::FULLSTACK,
            self::QA_TESTER,
        ];
    }

    /**
     * Check if bidang is valid
     */
    public static function isValid(string $bidang): bool
    {
        return in_array($bidang, self::all());
    }
}

