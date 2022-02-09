<?php

namespace Bitfumes\ApiAuth\Tests\Feature;

use Bitfumes\ApiAuth\Tests\User;
use Bitfumes\ApiAuth\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Bitfumes\ApiAuth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function a_user_can_register_and_get_email_confirmation()
	{
		Notification::fake();
		$res = $this->postJson(route('user.register'), [
			'name'                  => 'sarthak',
			'email'                 => 'sarthak@bitfumes.com',
			'password'              => 'secret123',
			'password_confirmation' => 'secret123',
		]);

		$user = User::first();
		Notification::assertSentTo($user, VerifyEmail::class);
		// $this->assertNotNull(json_decode($res->getContent())->access_token);
		$this->assertDatabaseHas('users', ['email' => 'sarthak@bitfumes.com', 'email_verified_at' => null]);
	}

	/** @test */
	public function a_user_get_404_if_user_not_found_via_id_while_verifying_email()
	{
		$this->postJson(route('verification.verify', $notInDBUserId = 4000), ['signature' => 'random'])->assertStatus(404);
	}

	/** @test */
	public function user_can_resend_verify_email()
	{
		Notification::fake();
		$user = factory(User::class)->create();
		$this->postJson(route('verification.resend', $user->toArray()));
		Notification::assertSentTo($user, VerifyEmail::class);
	}

	/** @test */
	public function user_can_Verify_its_email()
	{
		Mail::fake();
		$user = factory(User::class)->create();
		$this->postJson(route('verification.verify', $user->id));
		$this->assertNotNull($user->fresh()->email_verified_at);
	}

	/** @test */
	public function for_registration_email_password_and_name_is_required()
	{
		$this->withExceptionHandling();
		$res = $this->post(route('user.register'));
		$res->assertSessionHasErrors(['email', 'password', 'name']);
	}

	/** @test */
	public function for_registration_email_must_be_real_email()
	{
		$this->withExceptionHandling();
		$res = $this->post(route('user.register'), ['email' => 'sarthak.com']);
		$this->assertEquals(session('errors')->get('email')[0], 'The email must be a valid email address.');
	}

	/** @test */
	public function for_registration_password_must_be_min_of_8_chars()
	{
		$this->withExceptionHandling();
		$res = $this->post(route('user.register'), ['password' => 'abcd', 'password_confirmation' => 'abcd']);
		$this->assertEquals(session('errors')->get('password')[0], 'The password must be at least 8 characters.');
	}

	/** @test */
	public function for_registration_name_must_be_max_of_25_chars()
	{
		$this->withExceptionHandling();
		$res = $this->post(route('user.register'), ['name' => 'ankur sarthak shrivastava savvy']);
		$this->assertEquals(session('errors')->get('name')[0], 'The name may not be greater than 25 characters.');
	}

	/** @test */
	public function a_user_can_login_with_email_and_password()
	{
		$user = factory(User::class)->create();
		$res  = $this->postJson(route('user.login'), ['email' => $user->email, 'password' => 'secret123'])->json();
		$this->assertEquals($user->email, auth()->user()->email);
		$this->assertNotNull($res['access_token']);
	}

	/** @test */
	public function for_login_email_password_required()
	{
		$this->withExceptionHandling();
		$res = $this->post(route('user.login'));
		$res->assertSessionHasErrors(['email', 'password']);
	}

	/** @test */
	public function for_login_email_must_be_real_email()
	{
		$this->withExceptionHandling();
		$res = $this->post(route('user.login'), ['email' => 'sarthak.com']);
		$this->assertEquals(session('errors')->get('email')[0], 'The email must be a valid email address.');
	}

	/** @test */
	public function for_login_password_must_be_min_of_8_chars()
	{
		$this->withExceptionHandling();
		$res = $this->post(route('user.login'), ['password' => 'abcd', 'password_confirmation' => 'abcd']);
		$this->assertEquals(session('errors')->get('password')[0], 'The password must be at least 8 characters.');
	}
}
