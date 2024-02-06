<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ToolProperty extends Model
{
    use HasFactory;

    /**
     * The columns that are fillable
     *
     * @var array
     */
    protected $fillable = ['tool_id','property_id', 'is_guest_allowed', 'value'];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

}
