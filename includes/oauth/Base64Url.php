<?php
/**
 * Base64url-encoding helpers conform RFC 4648 §5.
 */

declare(strict_types=1);

namespace PolitiekPraat\OAuth;

final class Base64Url
{
    public static function encode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function decode(string $data): string
    {
        $padded = strtr($data, '-_', '+/');
        $pad = strlen($padded) % 4;
        if ($pad > 0) {
            $padded .= str_repeat('=', 4 - $pad);
        }
        $decoded = base64_decode($padded, true);
        return $decoded === false ? '' : $decoded;
    }
}
