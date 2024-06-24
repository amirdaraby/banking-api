<?php

namespace App\Http\Resources\Card;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Account\ListResource as AccountListResource;
use App\Http\Resources\User\ListResource as UserListResource;

class ShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->account_id,
            'number' => $this->number,
            'expiration_year' => $this->expiration_year,
            'expiration_month' => $this->expiration_month,
            'cvv2' => $this->cvv2,
            'account' => AccountListResource::make($this->account),
            'user' => UserListResource::make($this->account->user)
        ];
    }
}
