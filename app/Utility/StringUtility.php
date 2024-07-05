<?php

namespace Utility;

class StringUtility
{
    public static function replaceWithUnderscore($string): string
    {
        // verwijder spaties voor en na string
        $string = trim($string);
        // Verwijder speciale tekens en vervang door underscore
        $string = preg_replace("/[^a-zA-Z0-9.]/", "_", $string);
        return $string;
    }
}
