<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;

class WebpageSimulator implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.what-is-my-screen-resolution', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'url' => 'required|url',
        ]);

        $desktops = [
            '1024x600' => '10" Notebook (1024x600)',
            '1024x768' => '12" Notebook (1024x768)',
            '1280x800' => '13" Notebook (1280x800)',
            '1366x768' => '15" Notebook (1366x768)',
            '1440x900' => '19" Desktop (1440x900)',
            '1600x900' => '20" Desktop (1600x900)',
            '1680x1050' => '22" Desktop (1680x1050)',
            '1920x1080' => '23" Desktop (1920x1080)',
            '1920x1200' => '24" Desktop (1920x1200)',
        ];

        $tablets = [
            '800x480' => 'Kindle HD 7"  (800x480)',
            '960x600' => 'ASUS Nexus 7 (960x600)',
            '1024x800' => 'Apple iPad (1024x800)',
            '1024x600' => 'Samsung Tab 7" (1024x600)',
            '1280x800' => 'Kindle Fire HD 8.9" (1280x800)',
            '1600x900' => 'Apple Ipad Pro (1600x900)',
            '1440x1024' => 'MS Surface (1440x1024)',
        ];

        $mobiles = [
            '240x320' => 'Kindle HD 7"  (240x320)',
            '320x480' => 'BlackBerry 8300 (320x480)',
            '320x480' => 'iPhone 3/4 (320x480)',
            '360x640' => 'Samsung S3-7" (360x640)',
            '375x667' => 'iPhone 6/7" (375x667)',
            '414x736' => 'iPhone 6/7 Plus (414x736)',
        ];

        $televisions = [
            '640x480' => '480p Television  (640x480)',
            '1280x720' => '720p Television (1280x720)',
            '1920x1080' => 'FHD Television (1920x1080)',
            '2560x1440' => 'WQHD Television (2560x1440)',
            '3840x2160' => '4K UHD Television (3840x2160)',
        ];

        $results = [
            'url' => $request->url,
            'desktops' => $desktops,
            'tablets' => $tablets,
            'mobiles' => $mobiles,
            'televisions' => $televisions,
        ];

        return view('tools.what-is-my-screen-resolution', compact('results', 'tool'));
    }
}
