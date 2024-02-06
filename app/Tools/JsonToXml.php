<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use App\Helpers\Classes\ArrayToXml;

class JsonToXml implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.json-to-xml', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'json' => 'required|min:1',
        ]);

        $json = $request->input('json');
        $json_array = json_decode($json, true);

        $converted = ArrayToXml::convert($json_array);
        $results = [
            'json' => $json,
            'xml' => $converted
        ];

        return view('tools.json-to-xml', compact('results', 'tool'));
    }
}
