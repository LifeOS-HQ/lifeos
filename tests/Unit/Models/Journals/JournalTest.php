<?php

namespace Tests\Unit\Models\Journals;

use App\Models\Journals\Journal;
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
}
