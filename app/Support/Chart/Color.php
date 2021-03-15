<?php

namespace App\Support\Chart;

class Color
{
    public static $used = [];

    const COLORS = [
        '#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9', '#f15c80', '#e4d354', '#2b908f', '#f45b5b', '#91e8e1'
    ];

    public static function get(int $key) : string
    {
        if (array_key_exists($key, self::COLORS)) {
            return self::COLORS[$key];
        }

        $colors_count = count(self::COLORS);
        $key = $key - (round($key / $colors_count, 0) * $colors_count);

        return self::get($key);
    }

    public static function random() : string
    {
        $available_colors = array_diff(self::COLORS, self::$used);
        $color_key = array_rand($available_colors);

        $color = self::get($color_key);
        self::$used[] = $color;

        return $color;
    }


}