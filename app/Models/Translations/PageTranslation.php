<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'page_translations';

    protected $fillable = ['title', 'slug', 'content', 'excerpt', 'meta_title', 'meta_description', 'og_title', 'og_description', 'og_image'];
}
