<?php

namespace Php\Tests;
//class utilities
// ciri dari class utilitis adalah isinya cuma static function saja
class Math
{

    public static function sum(array $values): int
    {
        $total = 0;
        foreach ($values as $value) {
            $total += $value;
        }
        return $total;
    }
}