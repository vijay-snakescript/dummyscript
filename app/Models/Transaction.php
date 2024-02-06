<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SearchableTrait;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'email', 'address_lane_1', 'address_lane_2',
        'postal_code', 'country_code', 'currency', 'amount', 'plan_id', 'plan_type', 'gateway', 'transaction_id', 'status', 'payment_gateway',
        'expiry_date', 'response'
    ];

    protected $dates = ['expiry_date'];

    /**
     * Trasaction belongs to user
     *
     * @return collection
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Trasaction belongs to plan
     *
     * @return collection
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Trasaction scope plan
     *
     * @return collection
     */
    public function scopePlan($query)
    {
        return $query->where('plan_id', '!=', '0');
    }

    /**
     * Trasaction scope active
     *
     * @return collection
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeActiveOrCancelled($query)
    {
        return $query->where(function ($query) {
            $query->where('status', 1)->orWhere('status', 2);
        });
    }

    public function scopeBank($query)
    {
        return $query->where('payment_gateway', 'banktransfer');
    }
}
