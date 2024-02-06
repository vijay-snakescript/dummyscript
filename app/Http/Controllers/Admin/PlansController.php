<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use App\Models\Tool;
use App\Models\Language;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\PlanProperty;
use Illuminate\Http\Request;
use App\Helpers\Facads\Payment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PlanRequest;

class PlansController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locales = Language::getLocales();
        $search = $request->get('q', false);

        $plans = Plan::with('translations')
            ->when(!empty($search), function ($query) use ($search) {
                $query->search($search, null, true);
            })
            ->paginate();

        return view('plans.index', compact('locales', 'plans'));
    }

    public function create(Request $request)
    {
        $locales = Language::getLocales();
        $tools = Tool::with('translations')->with('PlanProperties')->active()->whereNotNull('properties->properties')->get();
        $properties = Property::with('translations')->active()->get();

        return view('plans.create', compact('locales', 'tools', 'properties'));
    }

    public function store(PlanRequest $request)
    {
        $plan = Plan::create($request->only('yearly_price', 'monthly_price', 'discount', 'is_api_allowed', 'is_ads'));

        $langs = Language::getLocales();
        foreach ($langs as $lang) {
            $translation = $request->only($lang->locale);
            if ($translation[$lang->locale]['name']) {
                $plan->fill($translation);
            }
        }
        $plan->save();

        $tools = Tool::all();
        $properties = Property::active()->get();
        foreach ($tools as $tool) {
            if (isset($tool->properties['properties'])) {
                foreach ($properties->whereIn('prop_key', $tool->properties['properties']) as $property) {
                    $value = $request->input('property_' . $tool->id . '_' . $property->id) ? $request->input('property_' . $tool->id . '_' . $property->id) : 0;
                    $proArray = [
                        'property_id' => $property->id,
                        'plan_id' => $plan->id,
                        'tool_id' => $tool->id,
                        'value' => $value
                    ];

                    PlanProperty::create($proArray);
                }
            }
        }

        return redirect()->route('admin.plans')->withSuccess(__('admin.planCreated'));
    }


    public function edit(Request $request, Plan $plan)
    {
        $locales = Language::getLocales();
        $tools = Tool::with('translations')->with('PlanProperties')->active()->whereNotNull('properties->properties')->get();
        $properties = Property::with('translations')->active()->get();

        return view('plans.edit', compact('locales', 'tools', 'properties', 'plan'));
    }

    /**
     *
     */
    public function update(PlanRequest $request, Plan $plan)
    {
        $plan->fill($request->only('yearly_price', 'monthly_price', 'discount'));
        $plan->is_api_allowed = $request->input('is_api_allowed', false);
        $plan->is_ads = $request->input('is_ads', false);
        $langs = Language::getLocales();
        foreach ($langs as $lang) {
            $translation = $request->only($lang->locale);
            if ($translation[$lang->locale]['name']) {
                $plan->fill($translation);
            }
        }
        $plan->save();

        $tools = Tool::all();
        $properties = Property::all();
        foreach ($tools as $tool) {
            if (isset($tool->properties['properties'])) {
                foreach ($properties->whereIn('prop_key', $tool->properties['properties']) as $property) {
                    $value = $request->input('property_' . $tool->id . '_' . $property->id) ? $request->input('property_' . $tool->id . '_' . $property->id) : 0;
                    $proArray = [
                        'property_id' => $property->id,
                        'plan_id' => $plan->id,
                        'tool_id' => $tool->id,
                        'value' => $value
                    ];

                    $toolProperty = PlanProperty::where('tool_id', $tool->id)->where('plan_id', $plan->id)->where('property_id', $property->id)->first();
                    if (isset($toolProperty->id)) {
                        $toolProperty->update($proArray);
                    } else {
                        $toolProperty = PlanProperty::create($proArray);
                    }
                }
            }
        }

        return redirect()->route('admin.plans')->withSuccess(__('admin.planUpdated'));
    }

    public function statusChange($id, $status)
    {
        $plan = Plan::find($id);
        $plan->update(['status' => $status]);

        return redirect()->route('admin.plans')->withSuccess(__('admin.planUpdated'));
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()->back()->withSuccess(__('admin.planDeleted'));
    }

    public function transactions()
    {
        $transactions = Transaction::with(['plan', 'user'])
            ->whereHas('plan', function ($query) {
                $query->orWhere('transactions.plan_id', 0);
            })
            ->has('user')
            ->paginate();

        return view('plans.transactions', compact('transactions'));
    }


    public function createPlanSusbcription($plan = null)
    {
        $gateways = Payment::all();
        foreach ($gateways as $key => $gateway) {
            $gateway->createPlan($plan);
        }
    }

    public function bankTransfer()
    {
        $transactions = Transaction::with(['plan', 'user'])->bank()->has('plan')->has('user')->paginate();

        return view('plans.bankTransfer', compact('transactions'));
    }

    public function banktransferStatusChange($id, $status)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => $status]);
        if ($status == 1) {

            $transaction->expiry_date = $transaction->plan_type == "yearly" ? now()->addYear() : now()->addMonth();
            $transaction->status = 1;
            $transaction->response = __('document.successfull');
            $transaction->update();
        }
        return redirect()->back()->withSuccess(__('admin.planUpdated'));
    }
}
