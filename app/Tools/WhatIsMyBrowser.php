<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use App\Helpers\Classes\Browser;

class WhatIsMyBrowser implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        $browser = new Browser($request->userAgent());
        $result = [
            'browser' => $browser->getBrowser(),
            'platform' => $browser->getPlatform(),
            'version' => $browser->getVersion(),
            'agent' => $browser->getUserAgent(),
            'languages' => $request->getPreferredLanguage(),
        ];

        return view('tools.what-is-my-browser', compact('tool', 'result'));
    }

    public function handle(Request $request, Tool $tool)
    {
        abort(404);
    }
}
