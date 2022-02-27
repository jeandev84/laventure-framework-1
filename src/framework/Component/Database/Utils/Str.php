<?php
namespace Laventure\Component\Database\Utils;

/**
 * @Str
*/
class Str
{
    /**
     * @param $str
     * @param int $start
     * @param int $length
     * @return false|string
    */
    public static function substr($str, int $start = 0, int $length = 1)
    {
        return substr($str, $start, $length);
    }


    /**
     * @param $str
     * @return string
    */
    public static function toLower($str): string
    {
        return strtolower($str);
    }
}