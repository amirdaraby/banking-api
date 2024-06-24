<?php

namespace App\Paginator;

use Illuminate\Pagination\LengthAwarePaginator;

class CustomLengthAwarePaginator extends LengthAwarePaginator
{
    public function toArray(): array
    {
        return [
            'current_page' => $this->currentPage(),
            'items' => $this->items->toArray(),
            'per_page' => $this->perPage(),
            'total' => $this->total(),
            'last_page' => $this->perPage(),
        ];
    }
}
