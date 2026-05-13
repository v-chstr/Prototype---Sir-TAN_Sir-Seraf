<?php

namespace App\Helpers;

/**
 * Frontend-only anonymization helper.
 * Database stores real names — this helper masks them for display only.
 * Uses HMAC-based hashing to produce consistent, non-reversible anonymous identifiers.
 */
class AnonymizeHelper
{
    /**
     * Application-level secret key for HMAC generation.
     * Derived from APP_KEY so it's consistent per environment.
     */
    private static function getSecret(): string
    {
        return hash('sha256', config('app.key') . '::anonymize-salt');
    }

    /**
     * Generate a consistent anonymous identifier for a user.
     * Same user always produces the same anonymous ID.
     *
     * @param int|string $userId  The user's database ID (primary key)
     * @param string     $prefix  Label prefix (e.g., 'Respondent', 'User')
     * @return string             e.g., "Respondent-A7F3"
     */
    public static function anonymizeUser(int|string $userId, string $prefix = 'Respondent'): string
    {
        $hash = hash_hmac('sha256', (string) $userId, static::getSecret());
        $code = strtoupper(substr($hash, 0, 6));

        return "{$prefix}-{$code}";
    }

    /**
     * Mask an email address for frontend display.
     * e.g., "john.doe@spup.edu.ph" → "r••••••@••••.•••"
     *
     * @param int|string $userId
     * @return string
     */
    public static function anonymizeEmail(int|string $userId): string
    {
        $hash = hash_hmac('sha256', (string) $userId, static::getSecret());
        $code = strtolower(substr($hash, 0, 4));

        return "{$code}••••@••••.•••";
    }

    /**
     * Generate an anonymous avatar initial (single letter).
     *
     * @param int|string $userId
     * @return string
     */
    public static function anonymizeInitial(int|string $userId): string
    {
        $hash = hash_hmac('sha256', (string) $userId, static::getSecret());
        // Map first byte to A-Z
        $index = hexdec(substr($hash, 0, 2)) % 26;

        return chr(65 + $index); // A-Z
    }
}
