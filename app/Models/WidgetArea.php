<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WidgetArea extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'title', 'description', 'order'
    ];

    /**
     * WidgetArea has many widgets
     *
     * @return collection
     */
    public function widgets()
    {
        return $this->hasMany(Widget::class, 'widget_area_id', 'id')->orderBy('order', 'ASC');
    }
}
