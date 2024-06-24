<?php

namespace App\Http\Resources\Card;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'account_id' => $this->account_id,
            'number' => $this->number,
            'expiration_year' => $this->expiration_year,
            'expiration_month' => $this->expiration_month,
            'cvv2' => $this->cvv2,
        ];
    }
}
