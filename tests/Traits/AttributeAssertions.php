<?php

namespace Tests\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait AttributeAssertions
{
    public function assertSetsFormattedDatetime(string $class, string $attribute)
    {
        $datetime = today()->setTime(12, 34, 0);
        $attribute_formatted = $attribute . '_formatted';

        $model = new $class();
        $model->$attribute_formatted = $datetime->format('d.m.Y H:i');

        $this->assertEquals($datetime->format('Y-m-d H:i:s'), $model->$attribute);

        $model = new $class([
            $attribute_formatted => $datetime->format('d.m.Y H:i'),
        ]);

        $this->assertEquals($datetime->format('Y-m-d H:i:s'), $model->$attribute);
    }

    public function assertSetsFormattedDate(string $class, string $attribute)
    {
        $datetime = today()->setTime(0, 0, 0);
        $attribute_formatted = $attribute . '_formatted';

        $model = new $class();
        $model->$attribute_formatted = $datetime->format('d.m.Y');

        $this->assertEquals($datetime->format('Y-m-d H:i:s'), $model->$attribute);

        $model = new $class([
            $attribute_formatted => $datetime->format('d.m.Y'),
        ]);

        $this->assertEquals($datetime->format('Y-m-d H:i:s'), $model->$attribute);
    }

    public function assertSetsFormattedNumber(string $class, string $attribute)
    {
        $attribute_formatted = $attribute . '_formatted';
        $number = 1234.560000;
        $number_formatted = '1234,56';

        $model = new $class();
        $model->$attribute_formatted = $number_formatted;

        $this->assertEquals($number, $model->$attribute);

        $model = new $class([
            $attribute_formatted => $number_formatted,
        ]);

        $this->assertEquals($number, $model->$attribute);
    }

    public function assertSetsFormattedNullableNumber(string $class, string $attribute)
    {
        $attribute_formatted = $attribute . '_formatted';
        $number = 1234.560000;
        $number_formatted = '1234,56';

        $model = new $class();
        $model->$attribute_formatted = null;

        $this->assertNull($model->$attribute);

        $this->assertSetsFormattedNumber($class, $attribute);
    }
}

?>