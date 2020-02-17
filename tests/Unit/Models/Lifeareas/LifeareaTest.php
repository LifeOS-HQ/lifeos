<?php

namespace Tests\Unit\Models\Lifeareas;

use App\Models\Lifeareas\Lifearea;
use App\Models\Lifeareas\Scale;
use App\Models\Reviews\Review;
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
    public function it_belongs_to_an_user()
    {
        $model = factory(Lifearea::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);
        $this->assertEquals(BelongsTo::class, get_class($model->user()));
    }

    /**
     * @test
     */
    public function it_has_many_scales()
    {
        $model = factory(Lifearea::class)->create();
        $related = $model->scales->first();

        $this->assertHasMany($model, $related, 'scales', 10);
    }

    /**
     * @test
     */
    public function it_has_many_ratings()
    {
        $model = factory(Lifearea::class)->create();
        $related = factory(Review::class)->create([
            'user_id' => $model->user_id,
        ]);

        $this->assertHasMany($model, $related, 'ratings');
    }

    /**
     * @test
     */
    public function it_creates_scales_after_it_is_created()
    {
        $model = factory(Lifearea::class)->create();
        $this->assertCount(10, $model->scales);
    }

    /**
     * @test
     */
    public function it_deletes_its_scales_on_deleting()
    {
        $model = factory(Lifearea::class)->create();
        $model->delete();

        $this->assertDatabaseMissing('lifearea_scale', [
            'lifearea_id' => $model->id,
        ]);
    }
}
