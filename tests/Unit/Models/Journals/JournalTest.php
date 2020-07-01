<?php

namespace Tests\Unit\Models\Journals;

use App\Models\Journals\Activities\Activity;
use App\Models\Journals\Gratitude\Gratitude;
use App\Models\Journals\Journal;
use App\Models\Journals\Rating;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;
use Tests\Traits\RelationshipAssertions;

class JournalTest extends TestCase
{
    use RelationshipAssertions;

    /**
     * @test
     */
    public function it_sets_its_name_on_creating()
    {
        $model = factory(Journal::class)->create([
            'date' => '2020-01-15',
        ]);
        $this->assertEquals('Eintrag vom 15.01.2020', $model->name);
    }

    /**
     * @test
     */
    public function it_knows_if_it_is_deletable()
    {
        $model = factory(Journal::class)->create();
        $this->assertTrue($model->isDeletable());
    }

    /**
     * @test
     */
    public function it_belongs_to_an_user()
    {
        $model = factory(Journal::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);
        $this->assertEquals(BelongsTo::class, get_class($model->user()));
    }

    /**
     * @test
     */
    public function it_has_many_activities()
    {
        $model = factory(Journal::class)->create();
        $related = factory(Activity::class)->create([
            'journal_id' => $model->id,
            'user_id' => $model->user_id,
        ]);

        $this->assertHasMany($model, $related, 'activities');
    }

    /**
     * @test
     */
    public function it_has_many_gratitudes()
    {
        $model = factory(Journal::class)->create();
        $related = factory(Gratitude::class)->create([
            'journal_id' => $model->id,
            'user_id' => $model->user_id,
        ]);

        $this->assertHasMany($model, $related, 'gratitudes');
    }

    /**
     * @test
     */
    public function it_has_many_ratings()
    {
        $model = factory(Journal::class)->create();
        $related = factory(Rating::class)->create([
            'journal_id' => $model->id,
            'user_id' => $model->user_id,
        ]);

        $this->assertHasMany($model, $related, 'ratings');
    }

    /**
     * @test
     */
    public function it_creates_ratings_after_it_is_created()
    {
        $model = factory(Journal::class)->create();

        $this->assertCount(0, $model->ratings);

        $model->ratings()->create([
            'title' => 'First Rating',
            'user_id' => $model->user_id,
        ]);

        $model->ratings()->create([
            'title' => 'Second Rating',
            'user_id' => $model->user_id,
        ]);

        $model = factory(Journal::class)->create();

        $this->assertCount(2, $model->ratings);
    }
}
