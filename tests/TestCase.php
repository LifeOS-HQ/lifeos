<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected $user;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function signIn(User $user = null)
    {
        if (is_null($user))
        {
            $user = $this->user;
        }

        if (! $this->isAuthenticated())
        {
            $this->actingAs($user);
        }

        return $user;
    }
}
