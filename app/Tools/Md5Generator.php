<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;

class Md5Generator implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.hash-generator', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'string' => 'required|min:1|max:255',
        ]);
        $string = $request->input('string');
        $results = [
            ['label' => __('common.originalText'), 'value' => $string],
            ['label' => __('tools.md5'), 'value' => md5($string)],
            ['label' => __('tools.base64'), 'value' => base64_encode($string)],
            ['label' => __('tools.bcrypt'), 'value' => bcrypt($string)],
            ['label' => __('tools.sha1'), 'value' => sha1($string)],
        ];

        return view('tools.hash-generator', compact('tool', 'string', 'results'));
    }


}
