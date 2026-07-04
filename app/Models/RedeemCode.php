<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RedeemCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'amount',
        'is_used',
        'is_disabled',
        'used_by',
        'used_at',
        'created_by',
        'batch_note',
        'expires_at',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'is_used'     => 'boolean',
        'is_disabled' => 'boolean',
        'used_at'     => 'datetime',
        'expires_at'  => 'datetime',
    ];

    public function usedByUser()
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isAvailable(): bool
    {
        return !$this->is_used && !$this->is_disabled && !$this->isExpired();
    }
}
