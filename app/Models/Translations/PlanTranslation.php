<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class PlanTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'plan_id', 'description'];
}
