<?php

namespace App\Http\Controllers;

use App\Models\Faqs;
use Illuminate\Http\Request;
use App\Helpers\Facads\Payment;
use Butschster\Head\Facades\Meta;

class AdsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request)
    {
        $plan = ads_plan();
        $gateways = Payment::all();

        $meta = __("static_pages.adsRemoval");
        Meta::setMeta((object) $meta);

        $faqs = Faqs::active()->pricing()->get();

        return view('advertisements.remove', compact('gateways', 'faqs', 'plan'));
    }
}
