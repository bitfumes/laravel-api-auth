<?php

namespace Bitfumes\ApiAuth\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $roleResource = config('api-auth.resources.user');
        return [
            'id'     => $this->id,
            'name'   => $this->name,
            'email'  => $this->email,
        ];
    }
}
