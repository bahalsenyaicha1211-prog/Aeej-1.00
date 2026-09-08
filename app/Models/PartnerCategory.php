<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PartnerCategory extends Model
{
    protected $fillable = ['nom', 'slug'];

    protected static function booted(): void
    {
        static::saving(function (self $categorie) {
            if (empty($categorie->slug) || $categorie->isDirty('nom')) {
                $categorie->slug = Str::slug($categorie->nom);
            }
        });
    }

    public function partners()
    {
        return $this->hasMany(Partner::class);
    }
}
