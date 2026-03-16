<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClickUpMapping extends Model
{
    protected $guarded = [];

    // Table name since it differs from 'click_up_mappings'
    protected $table = 'clickup_mappings';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'last_synced_at' => 'datetime',
        'raw_api_response' => 'array',
    ];

    /**
     * Get the inquiry that owns the mapping.
     */
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }
}
