<?php

namespace App\Http\Controllers;

use App\Models\Faqs;
use App\Models\Plan;
use App\Models\Tool;
use App\Models\Property;
use Illuminate\Http\Request;
use Butschster\Head\Facades\Meta;

class PlansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function plans()
    {
        $plans = Plan::active()
            ->with('properties')
            ->with('translations')
            ->get();

        $faqs = Faqs::active()->pricing()->get();
        $properties = Property::active()->with('translations')->get();
        $tools = Tool::active()
            ->with('PlanProperties')
            ->with('translations')
            ->get();

        $meta = __("static_pages.plans");
        Meta::setMeta((object) $meta);

        return view('plans.list', compact('tools', 'plans', 'faqs', 'properties'));
    }
}
