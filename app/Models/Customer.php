<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'phone', 'address', 'is_member'])] // <-- Pastikan ada 'address' dan 'is_member'
class Customer extends Model
{
    // Relasi: Satu pelanggan bisa punya banyak riwayat transaksi
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class)->latest();
    }
}
