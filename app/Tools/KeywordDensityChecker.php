<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use App\Helpers\Classes\SEOAnalyzer;

class KeyWordDensityChecker implements ToolInterface
{
    private $analyzer;

    public function render(Request $request, Tool $tool)
    {
        $type = 1;

        return view('tools.keyword-density-checker', compact('tool', 'type'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'content' => 'required_if:type,==,2',
            'url' => ['required_if:type,==,1', 'nullable', 'url'],
        ]);

        $title = $description = "";
        $headings = ['tags' => []];

        $analyzer = new SEOAnalyzer();
        $this->analyzer = $analyzer;
        $results = ['top' => collect([])];

        $content = $request->input('content');
        $url = $request->input('url');
        $type = $request->type;

        if ($request->type == 1) {
            $usableSource = $this->analyzer->getPageContent($url);
            $document = $this->analyzer->parseHtml($usableSource);

            list($title, $description) = $this->getTitleDescription($document);
            $headings = $this->analyzer->doHeaderResult($document);

            $content = $this->analyzer->getTextContent($usableSource);
        }

        $one_word_count = $this->analyzer->getKeywordDetails($content, 1, 2);
        $one_word_count = $this->keywordUsageLong($one_word_count, $headings, $title, $description);

        $two_word_count = $this->analyzer->getKeywordDetails($content, 2, 2);
        $two_word_count = $this->keywordUsageLong($two_word_count, $headings, $title, $description);

        $three_word_count = $this->analyzer->getKeywordDetails($content, 3, 2);
        $three_word_count = $this->keywordUsageLong($three_word_count, $headings, $title, $description);

        $four_word_count = $this->analyzer->getKeywordDetails($content, 4, 2);
        $four_word_count = $this->keywordUsageLong($four_word_count, $headings, $title, $description);

        $top = collect()
            ->push(...$two_word_count)
            ->push(...$three_word_count)
            ->sortByDesc('frequency')
            ->unique('keyword')
            ->take(10);

        $total_keywords =  array_sum(array_column($one_word_count, 'frequency')) +  array_sum(array_column($two_word_count, 'frequency')) +  array_sum(array_column($three_word_count, 'frequency')) +  array_sum(array_column($four_word_count, 'frequency'));
        $results = [
            'content' => $request->content,
            'top' => $top,
            'one_word_count' => $one_word_count,
            'two_word_count' => $two_word_count,
            'three_word_count' => $three_word_count,
            'four_word_count' => $four_word_count,
            'total_keywords' => $total_keywords,
            'url' => $url,
            'loadtime' => $this->analyzer->getLoadtime(),
            'domain' => $type == 1 ? extractHostname($url, true) : null,
        ];

        return view('tools.keyword-density-checker', compact('results', 'tool', 'type'));
    }

    private function getTitleDescription($document)
    {
        $headNode = $document->getElementsByTagName('head')->item(0);
        $titleNode = $headNode->querySelector('title');
        $title = null;
        if ($titleNode !== null) {
            $title = $this->analyzer->getTextContent($titleNode);
        }

        $description = null;
        $metaNodes = $headNode->querySelectorAll('meta');
        foreach ($metaNodes as $node) {
            $attributes = $node->getAttributes();
            if (isset($attributes['name']) && isset($attributes['content']) && strtolower($attributes['name']) === 'description') {
                $description = $attributes['content'];
            }
        }

        return [$title, $description];
    }

    private function keywordUsageLong($keywords, $headings, $title, $description)
    {
        $keywords_data = [];

        foreach ($keywords as $key => $keyword) {
            $header_check = false;
            foreach ($headings['tags'] as $headers) {
                if (in_array($key, array_keys($headers['longTailKeywords']))) {
                    $header_check = true;
                }
            }
            $keywords_data[$key]['keyword'] = $keyword['keyword'];
            $keywords_data[$key]['frequency'] = $keyword['frequency'];
            $keywords_data[$key]['headers'] = $header_check;
            $keywords_data[$key]['title'] = (strpos($title, $key)) ? true : false;
            $keywords_data[$key]['description'] = (strpos($description, $key)) ? true : false;
        }

        return $keywords_data;
    }
}
