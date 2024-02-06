<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;


class TagGenerator implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        $results['allow_follow'] = "follow";
        $results['allow_index'] = "index";
        $results['days'] = "1";
        $results['language'] = "English";
        $results['content_type'] = "UTF-8";

        return view('tools.tag-generator', compact('tool', 'results'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'site_title' => 'required',
            'site_description' => 'required',
            'site_keywords' => 'required',
            'allow_index' => 'required',
            'allow_follow' => 'required',
            'content_type' => 'required',
            'language' => 'required',
        ]);

        $site_title = $request->input('site_title');
        $site_description = $request->input('site_description');
        $site_keywords = $request->input('site_keywords');
        $allow_index = $request->input('allow_index');
        $allow_follow = $request->input('allow_follow');
        $content_type = $request->input('content_type');
        $language = $request->input('language');
        $author = $request->input('author');
        $days = $request->input('days');

        $meta = collect([]);
        $meta->push('<meta name="title" content="' . $site_title . '">');
        $meta->push('<meta name="description" content="' . $site_description . '">');
        $meta->push('<meta name="keywords" content="' . $site_keywords . '">');
        $meta->push('<meta name="robots" content="' . $allow_index . ',' . $allow_follow . '">');
        $meta->push('<meta http-equiv="Content-Type" content="text/html; charset=' . $content_type . '">');
        $meta->push('<meta name="language" content="' . $language . '">');
        if ($author != null) {
            $meta->push('<meta name="author" content="' . $author . '">');
        }
        if ($days != null && $days != '0') {
            $meta->push('<meta name="revisit-after" content="' . $days . ' days">');
        }

        $highlighted = highlight_metatags($meta->toArray());
        $normal_text = implode("\n", $meta->toArray());
        $converted_text = implode("\n", $highlighted);

        $results = [
            'converted_text' => $converted_text,
            'normal_text' => $normal_text,
            'site_title' => $site_title,
            'site_description' => $site_description,
            'site_keywords' => $site_keywords,
            'allow_index' => $allow_index,
            'allow_follow' => $allow_follow,
            'content_type' => $content_type,
            'language' => $language,
            'days' => $days,
            'author' => $author,
        ];

        return view('tools.tag-generator', compact('results', 'tool'));
    }
}
