<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdvertisementRequest;

class AdvertisementsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('q', false);

        $advertisements = Advertisement::when(!empty($search), function ($query) use ($search) {
            $query->search($search);
        })->paginate();

        return view('advertisement.index', compact('advertisements'));
    }

    public function create($type)
    {
        if (!in_array($type, ['1', '2', '3'])) {
            return redirect()->route('admin.advertisements');
        }

        return view('advertisement.create', compact('type'));
    }

    public function store(AdvertisementRequest $request)
    {
        $options = $request->options;
        if ($request->file("options.image")) {
            if ($image = fileUpload($request->file("options.image"))) {
                $options['image'] = $image;
            }
        }

        Advertisement::create([
            'title' => $request->title,
            'type' => $request->type,
            'options' => $options,
            'name' => $request->name,
        ]);

        return redirect()->route('admin.advertisements')->withSuccess(__('admin.adsCreated'));
    }


    public function edit(Request $request, Advertisement $advertisement)
    {
        return view('advertisement.edit', compact('advertisement'));
    }

    /**
     *
     */
    public function update(AdvertisementRequest $request, Advertisement $advertisement)
    {
        $options = $request->options;
        if ($request->file("options.image")) {
            if ($image = fileUpload($request->file("options.image"))) {
                $options['image'] = $image;
            }
        }
        $advertisement->update([
            'title' => $request->title,
            'type' => $request->type,
            'options' => $options,
            'name' => $request->name,
        ]);

        return redirect()->route('admin.advertisements')->withSuccess(__('admin.adsUpdated'));
    }

    public function statusChange($id, $status)
    {
        $ads = Advertisement::find($id);
        $ads->update(['status' => $status]);

        return redirect()->route('admin.advertisements')->withSuccess(__('admin.adsUpdated'));
    }

    public function destroy(Advertisement $advertisement)
    {
        $advertisement->delete();

        return redirect()->back()->withSuccess(__('admin.adsDeleted'));
    }
}
