<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $guarded = [];
    public function items()
    {
        return $this->hasMany(InquiryItem::class);
    }

    // علاقة غير مباشرة لجلب الموديلات المختارة فوراً
    public function modules()
    {
        return $this->belongsToMany(PriceModule::class, 'inquiry_items');
    }

    public function clickUpMapping()
    {
        return $this->hasOne(ClickUpMapping::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
