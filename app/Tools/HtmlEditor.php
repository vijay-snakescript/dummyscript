<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Contracts\ToolInterface;

class HtmlEditor implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.html-editor', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'string' => 'required|min:1',
        ]);

        $text = $request->input('string');

        $url = '/storage';
        $abspath = storage_path('app/public');
        $text = preg_replace('#(src=\"[\s\r\n]{0,})(' . $url . ')#', '$1' . $abspath, $text);
        $text = preg_replace('/(\<img[^>]+)(style\=\"[^\"]+\")([^>]+)(>)/', '${1}${3}${4}', $text);
        $text = preg_replace('/(\<img[^>]+)/', '${1}' . ' style="width:100%;"', $text);
        $pdf = PDF::loadView('tools.pdf.index', compact('text'));

        return $pdf->download('online-text-editor.pdf');
    }
}
