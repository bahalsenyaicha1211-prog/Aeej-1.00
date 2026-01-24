<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activite extends Model
{
    use HasFactory;

    protected $table = 'activites';
    protected $primaryKey = 'idacti';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['libelle','categorie','date'];

    protected $casts = [
        'date' => 'date',
    ];
}
