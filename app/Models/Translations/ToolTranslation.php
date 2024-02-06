<?php

namespace App\Models\Translations;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ToolTranslation extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'content', 'description', 'meta_title', 'meta_description', 'og_title', 'og_description' , 'index_content'];
}
