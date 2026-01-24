<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    use HasFactory;

    protected $table = 'pays';
    protected $primaryKey = 'idpays';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['nom'];

    public function membres()
    {
        return $this->hasMany(Membre::class, 'idpays', 'idpays');
    }
}
