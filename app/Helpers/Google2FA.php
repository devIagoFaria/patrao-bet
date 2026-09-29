<?php

namespace App\Helpers;

class Google2FA
{
    /**
     * Decode a base32 encoded string.
     *
     * @param string $base32
     * @return string
     */
    public static function base32Decode($base32)
    {
        $base32 = strtoupper($base32);
        if (!preg_match('/^[A-Z2-7=]+$/', $base32)) {
            return '';
        }

        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $decoded = '';
        $buffer = 0;
        $bufferSize = 0;

        for ($i = 0; $i < strlen($base32); $i++) {
            $char = $base32[$i];
            if ($char === '=') {
                break;
            }
            $val = strpos($alphabet, $char);
            $buffer = ($buffer << 5) | $val;
            $bufferSize += 5;

            if ($bufferSize >= 8) {
                $bufferSize -= 8;
                $decoded .= chr(($buffer >> $bufferSize) & 0xFF);
            }
        }

        return $decoded;
    }

    /**
     * Verify a 2FA TOTP code against a secret key.
     *
     * @param string $secret
     * @param string $code
     * @param int $variance Time step variance (default: 1 step = 30 seconds before/after)
     * @return bool
     */
    public static function verify2FACode($secret, $code, $variance = 1)
    {
        $key = self::base32Decode($secret);
        if (empty($key)) {
            return false;
        }

        $timeStep = 30;
        $currentTime = time();
        $code = str_replace(' ', '', $code); // Remove any spaces

        for ($i = -$variance; $i <= $variance; $i++) {
            $time = floor(($currentTime + ($i * $timeStep)) / $timeStep);
            
            // Pack time into binary 64-bit string
            $timeHex = str_pad(dechex($time), 16, '0', STR_PAD_LEFT);
            $timeBinary = pack('H*', $timeHex);

            // Compute HMAC-SHA1
            $hash = hash_hmac('sha1', $timeBinary, $key, true);

            // Extract dynamic truncated hash value
            $offset = ord($hash[19]) & 0x0F;
            $otp = (
                ((ord($hash[$offset]) & 0x7F) << 24) |
                ((ord($hash[$offset + 1]) & 0xFF) << 16) |
                ((ord($hash[$offset + 2]) & 0xFF) << 8) |
                (ord($hash[$offset + 3]) & 0xFF)
            ) % 1000000;

            if (str_pad($otp, 6, '0', STR_PAD_LEFT) === $code) {
                return true;
            }
        }

        return false;
    }
}
