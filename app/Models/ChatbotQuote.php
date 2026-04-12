<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotQuote extends Model
{
    // Maps to the 'chatbot_angebote' table
    protected $table = 'chatbot_angebote';

    protected $fillable = [
        'angebot_nummer',
        'user_id',
        'antworten',
        'gesamtsumme_schaetzung',
        'status',
        'pdf_pfad',
    ];

    protected $casts = [
        'antworten' => 'array',
    ];

    /**
     * Get the user that owns this quote.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
