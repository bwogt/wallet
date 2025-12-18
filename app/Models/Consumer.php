<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consumer extends Model
{
    protected $fillable = ['user_id', 'cpf'];

    /**
     * Get the user that owns the consumer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
