<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class TagTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'title', 'description'];
}
