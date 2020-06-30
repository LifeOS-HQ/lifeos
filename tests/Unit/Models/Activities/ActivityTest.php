<?php

namespace Tests\Unit\Models\Activities;

use App\Models\Activities\Activity;
use App\Models\Lifeareas\Lifearea;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    /**
     * @test
     */
    public function it_belongs_to_an_user()
    {
        $model = factory(Activity::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);
        $this->assertEquals(BelongsTo::class, get_class($model->user()));
    }

    /**
     * @test
     */
    public function it_belongs_to_a_lifearea()
    {
        $model = factory(Activity::class)->create([
            'lifearea_id' => factory(Lifearea::class)->create()->id,
        ]);
        $this->assertEquals(BelongsTo::class, get_class($model->user()));
    }
}
