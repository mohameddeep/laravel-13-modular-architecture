<?php

namespace App\Modules\Auth\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'email'        => $this->email,
            'phone'        => $this->phone,
            'image_url'    => $this->image_url,
            'is_active'    => $this->is_active,
            'otp_verified' => $this->otp_verified,
            'created_at'   => $this->created_at,
        ];
    }
}
