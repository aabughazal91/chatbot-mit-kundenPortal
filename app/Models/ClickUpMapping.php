<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClickUpMapping extends Model
{
    // Maps to the 'clickup_mappings' table
    protected $table = 'clickup_mappings';

    protected $fillable = [
        'anfrage_id',
        'clickup_aufgabe_id',
        'clickup_status_name',
        'zuletzt_synchronisiert_am',
        'rohe_api_antwort',
    ];

    protected $casts = [
        'zuletzt_synchronisiert_am' => 'datetime',
        'rohe_api_antwort'          => 'array',
    ];

    /**
     * Get the inquiry that owns this mapping.
     */
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class, 'anfrage_id');
    }
}
