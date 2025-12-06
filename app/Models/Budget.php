<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'limit',
        'start_date',
        'end_date',
    ];

    /**
     * Kolom yang harus di-cast ke tipe data tertentu.
     * HANYA cast kolom tanggal ke tipe 'date'.
     */
    protected $casts = [
        'start_date' => 'date', // <-- WAJIB
        'end_date' => 'date',   // <-- WAJIB
    ];

    /**
     * Relasi ke User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}