<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faqs;
use Illuminate\Http\Request;
use App\Http\Requests\FAQRequest;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $faqs = Faqs::display()->paginate(setting('admin_pagination', 10));

        return view('faqs.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('faqs.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param FAQRequest $request
     * @return Renderable
     */
    public function store(FAQRequest $request)
    {
        Faqs::create($request->all());

        return redirect()
            ->route('admin.faqs.index')
            ->withSuccess(trans('admin.faqAdded'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Faqs $faq)
    {
        return view('faqs.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(FAQRequest $request, Faqs $faq)
    {
        $input = $request->all();
        $faq->update($input);

        return redirect()
            ->route('admin.faqs.index')
            ->withSuccess(trans('admin.faqUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Faqs $faq)
    {
        $faq->delete();

        return redirect()
            ->route('admin.faqs.index')
            ->withSuccess(trans('admin.deleted'));
    }

    public function changeStatus(Faqs $faq, $status)
    {
        $faq->status = $status;
        $faq->update();

        return redirect()
            ->route('admin.faqs.index')
            ->withSuccess(trans('admin.faqUpdated'));
    }
}
