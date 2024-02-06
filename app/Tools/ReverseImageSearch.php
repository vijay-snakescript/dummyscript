<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;

class ReverseImageSearch implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.reverse-image-search', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $url_array = [];
        $keyword = $request->input('keyword', null);
        $url = $request->input('url', null);
        if ($request->file('file')) {
            $validated = $request->validate([
                'file' => "required|image|max:" . $tool->fs_tool * 1024
            ]);
            if ($image = fileUpload($request->file("file"))) {
                $image = url($image);
                $google = "https://lens.google.com/uploadbyurl?url=" . $image;
                $yandex = "https://yandex.com/images/search?source=collections&rpt=imageview&url=" . $image;
                $bing = "https://www.bing.com/images/searchbyimage?FORM=IRSBIQ&cbir=sbi&imgurl=" . $image;
            } else {
                return redirect()->back()->withError(__('common.somethingWentWrong'));
            }
        } elseif ($keyword != null) {
            $keyword = $request->keyword;
            $google = "https://www.google.com/search?tbm=isch&q=" . $keyword;
            $yandex = "https://yandex.com/images/search?text=" . $keyword;
            $bing = "https://www.bing.com/images/search?q=" . $keyword;
        } elseif ($request->url != null) {
            $url = $request->url;
            $google = "https://lens.google.com/uploadbyurl?url=" . $url;
            $yandex = "https://yandex.com/images/search?source=collections&rpt=imageview&url=" . $url;
            $bing = "https://www.bing.com/images/searchbyimage?FORM=IRSBIQ&cbir=sbi&imgurl=" . $url;
        } else {
            return redirect()->back()->withError(__('tools.invalidSearch'));
        }

        $results = [
            'keyword' => $keyword,
            'url' => $url,
            'searches' => [
                [
                    'name' => 'Google',
                    'url' => $google,
                    'icon' => 'google',
                ],
                [
                    'name' => 'Bing',
                    'url' => $bing,
                    'icon' => 'bing',
                ],
                [
                    'name' => 'Yandex',
                    'url' => $yandex,
                    'icon' => 'yandex',
                ],
            ]
        ];

        return view('tools.reverse-image-search', compact('results', 'tool'));
    }
}
