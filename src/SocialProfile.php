<?php

namespace Bitfumes\ApiAuth;

use Illuminate\Database\Eloquent\Model;

class SocialProfile extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        $user  = config('api-auth.models.user');
        return $this->belongsTo($user);
    }
}
