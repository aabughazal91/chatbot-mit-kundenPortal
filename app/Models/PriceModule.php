<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceModule extends Model
{
    // Maps to the 'preis_modules' table
    protected $table = 'preis_modules';

    protected $fillable = [
        'key',
        'bezeichnung_de',
        'beschreibung',
        'preis',
        'typ',
        'optionen',
        'kategorie',
        'ist_aktiv',
    ];

    protected $casts = [
        'optionen'  => 'array',
        'ist_aktiv' => 'boolean',
    ];

    /**
     * Get the inquiry items that use this module.
     */
    public function inquiryItems()
    {
        return $this->hasMany(InquiryItem::class, 'preis_module_id');
    }
}
