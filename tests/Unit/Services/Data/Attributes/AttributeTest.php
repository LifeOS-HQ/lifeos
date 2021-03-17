<?php

namespace Tests\Unit\Services\Data\Attributes;

use App\Models\Services\Data\Attributes\Attribute;
use Tests\TestCase;

class AttributeTest extends TestCase
{
    /**
     * @test
     */
    public function it_set_its_attribute_type()
    {
        $model = Attribute::factory()->make();
        $this->assertEquals(\App\Models\Services\Data\Attributes\Types\Base::class, get_class($model->attribute_type));

        $model = Attribute::factory()->create();
        $this->assertEquals(\App\Models\Services\Data\Attributes\Types\Base::class, get_class($model->attribute_type));

        $model->slug = 'steps';
        $this->assertEquals(\App\Models\Services\Data\Attributes\Types\Steps::class, get_class($model->attribute_type));

        $model = Attribute::factory()->create([
            'slug'  => 'steps'
        ]);
        $this->assertEquals(\App\Models\Services\Data\Attributes\Types\Steps::class, get_class($model->attribute_type));
    }
}
