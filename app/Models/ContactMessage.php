<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'message',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
