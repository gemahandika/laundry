<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\TransactionDetail;

#[Fillable([
    'invoice_number',
    'customer_id',
    'aroma_id',
    'user_id',
    'promo_id',
    'start_date',
    'end_date',
    'finished_at',
    'subtotal',
    'discount',
    'total_pay',
    'status',
    'payment_status',
    'notes',
    'taken_at'
])]
class Transaction extends Model
{
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function aroma()
    {
        return $this->belongsTo(Aroma::class);
    }

    protected $casts = [
        'created_at' => 'datetime',
        'finished_at' => 'datetime',
        'taken_at' => 'datetime', // Tambahkan ini agar tidak error lagi
    ];
}
