<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotQuote extends Model
{
    protected $fillable = [
        'quote_number',
        'answers',
        'estimate',
    ];

    protected $casts = [
        'answers' => 'array',
    ];
}
