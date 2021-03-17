<?php

namespace App\Models\Services\Data\Attributes\Types;

class Base
{
    public function value($raw)
    {
        return (float) $raw;
    }

    public function formatted($raw) : string
    {
        return number_format($this->value($raw), 2, ',', '.');
    }

    public function label() : string
    {
        return 'Count';
    }

    public function unit() : string
    {
        return '';
    }
}