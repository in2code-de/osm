<?php
declare(strict_types=1);
namespace In2code\Osm\Utility;

class ArrayUtility
{
    public static function htmlSpecialCharsOnArray(array $array): array
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = self::htmlSpecialCharsOnArray($value);
            } elseif (is_string($value)) {
                $value = htmlspecialchars($value);
            }
        }
        return $array;
    }
}
