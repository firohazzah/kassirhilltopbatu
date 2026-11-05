<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
    'invoice_number',
    'customer_name',
    'subtotal',
    'discount',
    'total',
    'payment_method',
    'amount_paid',
    'change_amount',
    'status',
    'notes',
    'order_number',
    'order_date',
    'order_type',
    'payment_status',
    'order_status',
];

    protected $casts = [
        'subtotal' => 'integer',
        'discount' => 'integer',
        'total' => 'integer',
        'amount_paid' => 'integer',
        'change_amount' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }}