<?php

namespace App\Http\Resources\User\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Card\ListResource as AccountCardsResource;

class ListResource extends JsonResource
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
            'user_id' => $this->user_id,
            'balance' => $this->balance,
            'number' => $this->number,
            'cards' => AccountCardsResource::collection($this->cards),
        ];
    }
}
