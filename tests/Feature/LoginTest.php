<?php

namespace Bitfumes\ApiAuth\Tests\Feature;

use Bitfumes\ApiAuth\Tests\User;
use Bitfumes\ApiAuth\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_provide_user_details()
    {
        $this->authUser();
        $user = auth()->user();
        $res  = $this->getJson(route('user.get'));
        $this->assertEquals($user->name, json_decode($res->getContent())->data->name);
    }

    /** @test */
    public function user_can_login_and_then_logout()
    {
        $user = factory(User::class)->create();
        $this->postJson(route('user.login'), ['email'=>$user->email, 'password'=>'secret123']);
        $this->assertTrue(auth()->check());
        $this->postJson(route('logout'));
        $this->assertFalse(auth()->check());
    }
}
