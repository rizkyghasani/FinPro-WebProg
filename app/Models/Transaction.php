<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Properti yang dapat diisi massal (Mass Assignable).
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'type', // 'income' atau 'expense'
        'amount',
        'description',
        'date',
    ];

    /**
     * Relasi ke User (setiap transaksi dimiliki oleh satu user).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Category (setiap transaksi memiliki satu kategori).
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}