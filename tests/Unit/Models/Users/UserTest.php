<?php

namespace Tests\Unit\Models\Users;

use App\Models\Journals\Journal;
use App\Models\Work\Month;
use App\Models\Work\Time;
use App\Models\Work\Year;
use App\User;
use Tests\TestCase;
use Tests\Traits\RelationshipAssertions;

class UserTest extends TestCase
{
    use RelationshipAssertions;

    /**
     * @test
     */
    public function it_has_many_journals()
    {
        $model = factory(User::class)->create();
        $related = factory(Journal::class)->create([
            'user_id' => $model->id,
        ]);

        $this->assertHasMany($model, $related, 'journals');
    }

    /**
     * @test
     */
    public function it_has_many_working_years()
    {
        $model = factory(User::class)->create();
        $related = factory(Year::class)->create([
            'user_id' => $model->id,
        ]);

        $this->assertHasMany($model, $related, 'working_years');
    }

    /**
     * @test
     */
    public function it_has_many_working_months()
    {
        $model = factory(User::class)->create();
        $related = factory(Month::class)->create([
            'user_id' => $model->id,
        ]);

        $this->assertHasMany($model, $related, 'working_months');
    }

    /**
     * @test
     */
    public function it_has_many_working_times()
    {
        $model = factory(User::class)->create();
        $related = factory(Time::class)->create([
            'user_id' => $model->id,
        ]);

        $this->assertHasMany($model, $related, 'working_times');
    }
}
