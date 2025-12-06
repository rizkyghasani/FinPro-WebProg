<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Goal extends Model
{
    use HasFactory;

    /**
     * Properti yang dapat diisi massal.
     */
    protected $fillable = [
        'user_id',
        'name',
        'target_amount',
        'current_amount',
        'due_date',
    ];

    /**
     * Relasi ke User (setiap tujuan dimiliki oleh satu user).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
