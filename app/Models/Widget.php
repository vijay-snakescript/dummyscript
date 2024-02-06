<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Widget extends Model
{
    use HasFactory;

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
        'name', 'title', 'widget_area_id', 'order', 'status', 'web', 'mobile', 'ajax', 'settings', 'transparent'
    ];

    /**
     * The attributes that has to be cast.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'object',
        'ajax' => 'integer'
    ];

    /**
     * Widgets belongs to area
     *
     */
    public function area()
    {
        return $this->belongsTo(WidgetArea::class, 'widget_area_id', 'id');
    }

    /**
     * Scope to get desktop widgets
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWeb($query)
    {
        return $query->where('web', 1);
    }

    /**
     * Scope to get mobile widgets
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMobile($query)
    {
        return $query->where('mobile', 1);
    }

    /**
     * Scope to get active widgets
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
