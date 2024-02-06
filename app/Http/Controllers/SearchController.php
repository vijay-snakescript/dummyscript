<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $search = $request->get('q');
        $tools = Tool::active()
            ->when(!empty($search), function ($query) use ($search) {
                $query->search($search);
            })
            ->with('translations')
            ->get()
            ->map(function ($tool) {
                return [
                    'name' => $tool->name,
                    'url' => route('tool.show', ['tool' => $tool->slug]),
                    'icon' => $this->toolIcon($tool)
                ];
            });

        return $tools;
    }

    protected function toolIcon(Tool $tool): string
    {
        $icon = '';
        if ($tool->icon_type == 'class') {
            $icon = "<i class=\"an-duotone an-{$tool->icon_class}\"></i>";
        } else if ($tool->getFirstMediaUrl('tool-icon')) {
            $icon = "<img src=" . $tool->getFirstMediaUrl('tool-icon') . " alt=" . $tool->name . ">";
        }

        return $icon;
    }
}
