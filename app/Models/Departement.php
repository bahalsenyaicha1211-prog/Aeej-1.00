<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    protected $table = 'departements';
    protected $primaryKey = 'iddep';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['nom'];

    public function membres()
    {
        return $this->hasMany(Membre::class, 'iddep', 'iddep');
    }
}