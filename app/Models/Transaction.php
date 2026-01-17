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
        'type', 
        'amount',
        'description',
        'date',
    ];

 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}