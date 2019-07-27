<?php

namespace Bitfumes\ApiAuth\Tests\Feature;

use Bitfumes\ApiAuth\Tests\User;
use Bitfumes\ApiAuth\Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Bitfumes\ApiAuth\Notifications\UserPasswordReset;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ResetPasswordTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function a_password_reset_link_email_can_be_sent()
    {
        Notification::fake();
        $user = factory(User::class)->create();
        $res  = $this->post(route('user.password.email'), ['email' => $user->email])->assertStatus(202);
        Notification::assertSentTo([$user], UserPasswordReset::class);
    }

    /** @test */
    public function a_user_can_change_its_password()
    {
        Notification::fake();
        $user = factory(User::class)->create();

        $this->post(route('user.password.email'), ['email' => $user->email]);
        Notification::assertSentTo([$user], UserPasswordReset::class, function ($notification) use ($user) {
            $token = $notification->token;
            $this->assertTrue(Hash::check('secret123', $user->password));

            $res = $this->post(route('user.password.request'), [
                'email'                 => $user->email,
                'password'              => 'newpassword',
                'password_confirmation' => 'newpassword',
                'token'                 => $token,
            ])->assertStatus(202);
            $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
            return true;
        });
    }
}
