<?php

namespace App\Models\Services\Data\Attributes\Types;

use Illuminate\Support\Arr;

class Factory
{
    const CLASSES = [
        'steps' => \App\Models\Services\Data\Attributes\Types\Steps::class,
    ];

    public static function make(string $slug, $raw = null)
    {
        $class_name = self::getClassName($slug);

        return new $class_name($raw);
    }

    public static function getClassName(string $slug) : string
    {
        return Arr::get(self::CLASSES, $slug, \App\Models\Services\Data\Attributes\Types\Base::class);
    }
}