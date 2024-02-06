<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use MikeMcLin\WpPassword\Facades\WpPassword;

class WordpressPasswordGenerator implements ToolInterface
{
    /**
     * Render tool view
     *
     * @param Request $request
     * @param Tool $tool
     *
     * @return view
     */
    public function render(Request $request, Tool $tool)
    {
        return view('tools.hash-generator', compact('tool'));
    }

    /**
     * Process request and display result
     *
     * @param Request $request
     * @param Tool $tool
     *
     * @return view
     */
    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'string' => "required|min:1",
        ]);
        $string = $request->input('string');
        $results = [
            ['label' => __('common.originalText'), 'value' => $string],
            ['label' => __('tools.wordpress'), 'value' => WpPassword::make($string)],
        ];

        return view('tools.hash-generator', compact('results', 'string', 'tool'));
    }
}
