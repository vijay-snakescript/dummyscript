<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('menus')->count() != 0) {
            return;
        }

        $menus = [[
            'menu' => ['name' => 'Main Menu'],
            'items' => [
                ['label' => 'Home', 'link' => 'front.index', 'target' => '_self', 'condition' => null, 'class' => '', 'is_route' => true, 'parameters' => 'null', 'parent' => null, 'sort' => '0']
            ]
        ]];

        //end of menu array
        foreach ($menus as $data) {
            $menuData = $data['menu'];
            $menu = Menu::create($menuData);
            $menuitems = $data['items'];

            foreach ($menuitems as $index => $item) {
                $menuitem = new MenuItem;
                $menuitem->label = $item['label'];
                $menuitem->link = $item['link'];
                $menuitem->target = $item['target'];
                $menuitem->class = $item['class'];
                $menuitem->is_route = $item['is_route'];
                $menuitem->parameters = $item['parameters'];
                $menuitem->parent = null;
                $menuitem->sort = $item['sort'];
                $menu->items()->save($menuitem);

                if (isset($item['children'])) {
                    $children = $item['children'] ?? [];
                    foreach ($children as $key => $value) {
                        $child = new MenuItem;
                        $child->label = $value['label'];
                        $child->link = $value['link'];
                        $child->target = $value['target'];
                        $child->class = $value['class'];
                        $child->is_route = $value['is_route'];
                        $child->parameters = $value['parameters'];
                        $child->parent = $menuitem->id;
                        $child->sort = $value['sort'];
                        $menu->items()->save($child);
                    }
                }
            }
        }
        //endforeach
    }
}
