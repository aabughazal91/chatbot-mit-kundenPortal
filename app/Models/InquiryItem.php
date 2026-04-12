<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InquiryItem extends Model
{
    // Maps to the 'anfrage_positionen' table
    protected $table = 'anfrage_positionen';

    protected $fillable = [
        'anfrage_id',
        'preis_module_id',
        'kunden_auswahl',
        'preis_zum_zeitpunkt',
        'menge',
    ];

    /**
     * Get the inquiry this item belongs to.
     */
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class, 'anfrage_id');
    }

    /**
     * Get the price module for this item.
     */
    public function priceModule()
    {
        return $this->belongsTo(PriceModule::class, 'preis_module_id');
    }
}
