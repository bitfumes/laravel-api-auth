<?php

namespace Bitfumes\ApiAuth\Traits;

use Illuminate\Support\Str;
use Bitfumes\ApiAuth\SocialProfile;
use Bitfumes\ApiAuth\Helpers\Upload;
use Illuminate\Support\Facades\Storage;

trait ApiAuth
{
    public static function bootApiAuth(): void
    {
        static::retrieved(function ($model) {
            $model->fillable = array_merge($model->fillable, ['avatar']);
        });
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $verify = app()['config']['api-auth.notifications.verify'];
        $this->notify(new $verify());
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $reset_notification = app()['config']['api-auth.notifications.reset'];
        $this->notify(new $reset_notification($token));
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function social()
    {
        return $this->hasMany(SocialProfile::class);
    }

    public function uploadProfilePic($image)
    {
        $path     = config('api-auth.avatar.path');
        $disk     = config('api-auth.avatar.disk');
        $height   = config('api-auth.avatar.thumb_height');
        $width    = config('api-auth.avatar.thumb_width');
        $filename = $path . '/' . Str::random();
        if ($this->avatar) {
            Storage::disk($disk)->delete("{$this->avatar}.jpg");
            Storage::disk($disk)->delete("{$this->avatar}_thumb.jpg");
        }
        $big    = Upload::resize($image, 400);
        $thumb  = Upload::resize($image, $width, $height);
        Storage::disk($disk)->put("{$filename}.jpg", $big);
        Storage::disk($disk)->put("{$filename}_thumb.jpg", $thumb);
        $this->update(['avatar' => "{$filename}"]);
    }
}
