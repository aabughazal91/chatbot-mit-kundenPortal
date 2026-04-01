<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceModule extends Model
{
    protected $casts = [
        'options' => 'array',   
        'customer_choice' => 'array',
    ];

    public function inquiryItems() {
    return $this->hasMany(InquiryItem::class);
}
    protected $guarded = [];
}
