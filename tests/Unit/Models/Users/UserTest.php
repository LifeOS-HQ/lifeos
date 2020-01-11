<?php

namespace Tests\Unit\Models\Users;

use App\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $model = factory(User::class)->create();
    }
}
