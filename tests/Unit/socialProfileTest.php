<?php

namespace Bitfumes\ApiAuth\Tests\Unit;

use Bitfumes\ApiAuth\Tests\User;
use Bitfumes\ApiAuth\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class socialProfileTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_user()
    {
        $user   = $this->createUser();
        $social = $this->createSocial(['user_id' => $user->id]);
        $this->assertInstanceOf(User::class, $social->user);
    }
}
