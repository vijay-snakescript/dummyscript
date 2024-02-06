<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostTranslation extends Model
{
    protected $table = 'post_translations';

    protected $fillable = ['title', 'slug', 'contents', 'meta_title', 'meta_description', 'og_title', 'og_description', 'excerpt'];
}
