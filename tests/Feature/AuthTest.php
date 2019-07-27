<?php

namespace Bitfumes\ApiAuth\Tests\Feature;

use Bitfumes\ApiAuth\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function api_can_update_user_details()
    {
        $user = $this->authUser();
        $res  = $this->patch(route('user.update'), [
            'name'  => $user->name,
            'email' => 'abc@def.com', ]);
        $this->assertEquals($user->name, json_decode($res->getContent())->data->name);
    }
}
