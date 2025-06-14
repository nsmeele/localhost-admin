<?php

namespace Utility;

final class StringUtility
{
    public static function replaceWithUnderscore($string): string
    {
        $string = trim($string);
        return preg_replace("/[^a-zA-Z0-9.]/", "_", $string);
    }
}
