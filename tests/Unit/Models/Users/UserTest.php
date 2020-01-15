<?php

namespace Tests\Unit\Models\Users;

use App\Models\Journals\Journal;
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
}
