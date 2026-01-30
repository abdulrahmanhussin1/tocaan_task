<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends BaseModel
{
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
