<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;


class UrlEncodeDecode implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        $type = 1;
        return view('tools.url-encode-decode', compact('tool', 'type'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'string' => 'required|string',
            'type' => 'required',
        ]);

        $type = $request->input('type', 1);
        $results = [
            'original_text' => $request->string,
            'converted_text' => $type == 1 ? urlencode($request->string) : urldecode($request->string),
            'type' => $type
        ];

        return view('tools.url-encode-decode', compact('results', 'tool', 'type'));
    }
}
