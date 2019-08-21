<?php

namespace Bitfumes\ApiAuth\Tests\Unit;

use Bitfumes\ApiAuth\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class UserTest extends TestCase
{
    /** @test */
    public function it_has_many_social_profiles()
    {
        $user   = $this->createUser();
        $social = $this->createSocial(['user_id' => $user->id]);
        $this->assertInstanceOf(Collection::class, $user->social);
    }
}
