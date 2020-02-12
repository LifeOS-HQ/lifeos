<?php

namespace Tests\Unit\Models\Reviews;

use App\Models\Reviews\Lifearea;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;
use Tests\Traits\AttributeAssertions;
use Tests\Traits\RelationshipAssertions;

class LifeareaTest extends TestCase
{
    use AttributeAssertions, RelationshipAssertions;

    /**
     * @test
     */
    public function it_belongs_to_a_lifearea()
    {
        $model = factory(Lifearea::class)->create([
            'user_id' => factory(User::class)->create()->id,
            'lifearea_id' => factory(\App\Models\Lifeareas\Lifearea::class)->create()->id,
        ]);
        $this->assertEquals(BelongsTo::class, get_class($model->lifearea()));
    }

    /**
     * @test
     */
    public function it_belongs_to_an_user()
    {
        $model = factory(Lifearea::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);
        $this->assertEquals(BelongsTo::class, get_class($model->user()));
    }
}
