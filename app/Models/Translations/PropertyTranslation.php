<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class PropertyTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'property_id', 'description'];
}
