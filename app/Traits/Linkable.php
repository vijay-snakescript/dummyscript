<?php

namespace App\Traits;

trait Linkable
{
    /**
     * Dynamicaly build page url's for menu
     *
     * @return collection
     */
    public function link($item, $params)
    {
        if (!isset($params['id'])) {
            return $item;
        }

        $id = $params['id'];
        $page = is_numeric($id) ? $this->with('translations')->find($id) : $this->with('translations')->slug($id)->first();
        if (!$page || !$page->hasTranslation()) {
            $item->link = null;

            return $item;
        }

        $item->label = $page->title;
        $item->parameters = ['slug' => $page->slug];

        return $item;
    }
}
