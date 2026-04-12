<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    // Maps to the 'anfragen' table
    protected $table = 'anfragen';

    protected $fillable = [
        'angebot_nummer',
        'sessions_id',
        'user_id',
        'geschätzter_gesamtpreis',
        'pdf_pfad',
        'status',
    ];

    /**
     * Get the line items (positions) of this inquiry.
     */
    public function items()
    {
        return $this->hasMany(InquiryItem::class, 'anfrage_id');
    }

    /**
     * Indirect relation to retrieve selected modules.
     */
    public function modules()
    {
        return $this->belongsToMany(PriceModule::class, 'anfrage_positionen', 'anfrage_id', 'preis_module_id');
    }

    /**
     * Get the ClickUp mapping for this inquiry.
     */
    public function clickUpMapping()
    {
        return $this->hasOne(ClickUpMapping::class, 'anfrage_id');
    }

    /**
     * Get the user that owns the inquiry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
