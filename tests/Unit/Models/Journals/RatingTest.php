<?php

namespace Tests\Unit\Models\Journals;

use App\Models\Journals\Journal;
use App\Models\Journals\Rating;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;
use Tests\Traits\AttributeAssertions;
use Tests\Traits\RelationshipAssertions;

class RatingTest extends TestCase
{
    use AttributeAssertions, RelationshipAssertions;

    /**
     * @test
     */
    public function it_belongs_to_a_journal()
    {
        $model = factory(Rating::class)->create([
            'user_id' => factory(User::class)->create()->id,
            'journal_id' => factory(Journal::class)->create()->id,
        ]);
        $this->assertEquals(BelongsTo::class, get_class($model->journal()));
    }

    /**
     * @test
     */
    public function it_belongs_to_an_user()
    {
        $model = factory(Rating::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);
        $this->assertEquals(BelongsTo::class, get_class($model->user()));
    }
}
