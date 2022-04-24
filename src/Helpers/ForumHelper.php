<?php

namespace TypiCMS\Modules\Forum\Helpers;

class ForumHelper
{
    /**
     * Convert any string to a color code.
     */
    public static function stringToColorCode(string $string): string
    {
        $code = dechex(crc32($string));

        return mb_substr($code, 0, 6);
    }
}
