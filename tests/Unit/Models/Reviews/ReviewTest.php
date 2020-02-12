<?php

namespace Tests\Unit\Models\Reviews;

use App\Models\Reviews\Block;
use App\Models\Reviews\Lifearea;
use App\Models\Reviews\Review;
use Tests\TestCase;
use Tests\Traits\AttributeAssertions;
use Tests\Traits\RelationshipAssertions;

class ReviewTest extends TestCase
{
    use AttributeAssertions, RelationshipAssertions;

    /**
     * @test
     */
    public function it_has_many_blocks()
    {
        $model = factory(Review::class)->create();
        $related = factory(Block::class)->create([
            'review_id' => $model->id,
            'user_id' => $model->user_id,
        ]);

        $this->assertHasMany($model, $related, 'blocks');
    }

    /**
     * @test
     */
    public function it_has_many_lifeareas()
    {
        $model = factory(Review::class)->create();
        $related = factory(Lifearea::class)->create([
            'review_id' => $model->id,
            'user_id' => $model->user_id,
        ]);

        $this->assertHasMany($model, $related, 'lifeareas');
    }

    /**
     * @test
     */
    public function it_creates_blocks_after_it_is_created()
    {
        $model = factory(Review::class)->create();

        $this->assertCount(1, $model->blocks);

        $model->blocks->first()->update([
            'title' => 'First Block',
        ]);

        $model->blocks()->create([
            'title' => 'Second Block',
            'user_id' => $model->user_id,
        ]);

        $model = factory(Review::class)->create();

        $this->assertCount(2, $model->blocks);
    }

    /**
     * @test
     */
    public function it_creates_lifeareas_after_it_is_created()
    {
        $lifearea = factory(\App\Models\Lifeareas\Lifearea::class)->create();
        $model = factory(Review::class)->create([
            'user_id' => $lifearea->user_id
        ]);

        $this->assertCount(1, $model->lifeareas);
    }
}
