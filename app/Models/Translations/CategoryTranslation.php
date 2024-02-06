<?php

namespace App\Models\Translations;


use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'meta_title', 'meta_description', 'title', 'description', 'icon'];
}
