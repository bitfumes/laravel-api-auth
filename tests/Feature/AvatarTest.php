<?php

namespace Bitfumes\ApiAuth\Tests\Feature;

use Bitfumes\ApiAuth\Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class AvatarTest extends TestCase
{
    /** @test */
    public function api_can_update_user_avatar_field()
    {
        Storage::fake('public');

        $user = $this->createUser(['avatar'=>null]);
        $this->actingAs($user);
        $image = $this->createBase64Image();

        $res  = $this->patchJson(route('user.update'), [
            'email'   => 'abc@def.com',
            'avatar'  => $image,
        ]);

        $this->assertNotNull($user->fresh()->avatar);

        $path     = config('api-auth.avatar.path');
        $disk     = config('api-auth.avatar.disk');
        Storage::disk('public')->assertExists($user->avatar . '.jpg');

        $this->assertEquals($user->name, json_decode($res->getContent())->data->name);
    }

    /** @test */
    public function api_can_delete_old_avatar_from_disk_and_create_new_avatar()
    {
        Storage::fake('public');

        $user = $this->createUser(['avatar'=>null]);
        $this->actingAs($user);
        $image = $this->createBase64Image();

        $res   = $this->patchJson(route('user.update'), [
            'email'   => 'abc@def.com',
            'avatar'  => $image,
        ]);

        $oldAvatar = $user->avatar;
        Storage::disk('public')->assertExists($oldAvatar . '.jpg');

        $res   = $this->patchJson(route('user.update'), [
            'email'   => 'abc@def.com',
            'avatar'  => $image,
        ]);

        Storage::disk('public')->assertMissing($oldAvatar . '.jpg');
        Storage::disk('public')->assertExists($user->avatar . '.jpg');

        $this->assertNotNull($user->fresh()->avatar);

        $this->assertEquals($user->name, json_decode($res->getContent())->data->name);
    }

    protected function createBase64Image()
    {
        $image = \Illuminate\Http\Testing\File::image('image.jpg');
        return 'data:image/png;base64,' . base64_encode(file_get_contents($image));
    }
}
