<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Country extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'capital',
        'region',
        'latitude',
        'longitude'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
