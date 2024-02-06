<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use App\Helpers\Classes\SEOAnalyzer;

class KeywordResearchTool implements ToolInterface
{
    private $analyzer;

    public function render(Request $request, Tool $tool)
    {
        return view('tools.keyword-research-tool', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'url' => 'required|fqdn',
        ]);

        $analyzer = new SEOAnalyzer();
        $this->analyzer = $analyzer;
        $results = ['top' => collect([])];
        $url = $request->url;

        $usableSource = $this->analyzer->getPageContent($url);
        $content = $this->analyzer->getTextContent($usableSource);

        $one_word_count = $this->analyzer->getKeywordDetails($content, 1, 1);
        $two_word_count = $this->analyzer->getKeywordDetails($content, 2, 2);
        $three_word_count = $this->analyzer->getKeywordDetails($content, 3, 3);
        $four_word_count = $this->analyzer->getKeywordDetails($content, 4, 4);

        $top = collect()->push(...$two_word_count)->push(...$three_word_count)->sortByDesc('frequency')->unique('keyword')->take(10);

        $results = [
            'content' => $request->content,
            'top' => $top,
        ];

        return view('tools.keyword-research-tool', compact('results', 'tool'));
    }
}
