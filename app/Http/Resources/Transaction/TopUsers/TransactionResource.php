<?php

namespace App\Http\Resources\Transaction\TopUsers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'card_id' => $this->card_id,
            'amount' => $this->amount,
            'type' => $this->type,
            'status' => $this->status,
            'transaction_cost' => $this->transaction_cost_amount,
            'started_at' => $this->created_at,
            'done_at' => $this->updated_at,
        ];
    }
}
