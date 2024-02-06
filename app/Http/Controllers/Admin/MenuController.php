<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use App\Helpers\Facads\MenuBuilder;
use App\Helpers\Classes\BuilderMenu;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuRequest;
use Illuminate\Support\Facades\Redirect;

class MenuController extends Controller
{
    public function __construct(BuilderMenu $BuilderMenu)
    {
        //
    }

    public function index(Request $request, Menu $menu = null)
    {
        $menus = Menu::get();
        $sections = MenuBuilder::all();

        if ($menu) {
            $menu = Menu::where('id', '=', $menu->id)
                ->with(
                    ['parent_items' => function ($q) {
                        $q->with('child')->orderBy('sort');
                    }]
                )
                ->first();
        }

        return view('menu.index', compact('menus', 'sections', 'menu'));
    }

    public function store(MenuRequest $request)
    {
        $menu = Menu::create($request->all());

        return Redirect::route('admin.menus', $menu)->withSuccess(__('admin.menuCreated'));
    }

    public function update(MenuRequest $request, Menu $menu)
    {
        $name = $request->input('name');
        if ($name != $menu->name) {
            $menu->name = $name;
            $menu->save();
        }

        $items = $request->input('items');
        $this->updateMenuItem($items, $menu);

        return Redirect::back()->withSuccess(__('admin.menuUpdated'));
    }

    public function addItems(Request $request, Menu $menu)
    {
        $items = $request->input('items');
        $source = $request->input('source');

        switch ($source) {
            case 'custom':
                $menu->items()->create([
                    'label' => $request->input('link_text'),
                    'target' => '_self',
                    'link' => $request->input('url'),
                    'is_route' => false,
                    'parameters' => null,
                    'condition' => null,
                    'sort' => 1,
                    'parent' => null,
                ]);
                break;
            default:
                $menuBuilder = MenuBuilder::get($source);
                collect($items)->each(function ($item, $index) use ($menu, $menuBuilder) {
                    $items = $menuBuilder->items;
                    if (isset($items[$item])) {
                        $data = $items[$item];
                        $menu->items()->create([
                            'label' => $data->label(),
                            'target' => $data->target(),
                            'icon' => $data->icon(),
                            'link' => $data->route(),
                            'is_route' => $data->type() === 'route',
                            'parameters' => $data->params(),
                            'condition' => null,
                            'sort' => $index + 1,
                            'parent' => null,
                        ]);
                    }
                });
                break;
        }

        return Redirect::back()->withSuccess(__('admin.menuUpdated'));
    }

    protected function updateMenuItem($items, Menu $menu)
    {
        $index = 0;
        foreach($items as $item) {
            $index++;
            $item['parameters'] = json_decode($item['parameters'], true);
            $menuitem = $menu->items()->findOrNew($item['id']);
            $item['sort'] = $index;
            $menuitem->fill($item);
            $menuitem->save();
        }
    }

    public function destroy(Menu $menu)
    {
        $menu->items()->delete();
        $menu->delete();

        return Redirect::route('admin.menus')->withSuccess(__('admin.menuDeleted'));
    }

    public function destroyItem(Request $request, Menu $menu, MenuItem $item)
    {
        $item->delete();
        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return Redirect::route('admin.menus', $menu)->with('success');
    }
}
