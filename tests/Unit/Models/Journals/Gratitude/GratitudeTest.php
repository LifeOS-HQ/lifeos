<?php

namespace Tests\Unit\Models\Journals\Gratitude;

use App\Models\Journals\Gratitude\Gratitude;
use App\Models\Journals\Journal;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class GratitudeTest extends TestCase
{
    /**
     * @test
     */
    public function it_knows_if_it_is_deletable()
    {
        $model = factory(Gratitude::class)->create();
        $this->assertTrue($model->isDeletable());
    }

    /**
     * @test
     */
    public function it_belongs_to_an_user()
    {
        $related = factory(Journal::class)->create();

        $model = factory(Gratitude::class)->create([
            'user_id' => $related->user_id,
            'journal_id' => $related->id,
        ]);
        $this->assertEquals(BelongsTo::class, get_class($model->user()));
    }

    /**
     * @test
     */
    public function it_belongs_to_a_journal()
    {
        $related = factory(Journal::class)->create();

        $model = factory(Gratitude::class)->create([
            'user_id' => $related->user_id,
            'journal_id' => $related->id,
        ]);
        $this->assertEquals(BelongsTo::class, get_class($model->journal()));
    }
}
