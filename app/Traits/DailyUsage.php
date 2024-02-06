<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Shetabit\Visitor\Traits\Visitable;
use Illuminate\Support\Facades\Request;

trait DailyUsage
{
    use Visitable;

    public function usageToday()
    {
        return $this->visitLogs()
            ->where('created_at', '>=', Carbon::today()->toDateString())
            ->where(function ($query) {
                if (Auth::check()) {
                    $query->where('visitor_id', Auth::id());
                } else {
                    $query->where('ip', Request::ip());
                }
            });
    }
}
