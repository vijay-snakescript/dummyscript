<?php

namespace App\Tools;

use App\Models\Tool;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class MetaTagAnalyzer implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.meta-tag-analyzer', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'domain' => 'required|url',
        ]);

        $domain = extractHostname($request->domain);
        $pageSource = makeHttpRequest($request->domain);

        try {
            $client = new Client();
            $response = $client->request('GET', $request->domain, [
                'curl' => guzzleCurlOptions(),
                'stream' => true
            ]);

            $pageSize = null; //$response->getHeader('Content-Length');
            $contents = $response->getBody()->getContents();
            if ($pageSize == NULL) {
                $pageSize = mb_strlen($contents, '8bit');
            }
        } catch (ConnectException $e) {
            $message = $e->getHandlerContext()['error'] ?? $e->getMessage();

            return redirect()->back()->withError($message);
        } catch (ClientException $e) {
            $message = $e->getMessage();

            return redirect()->back()->withError($message);
        } catch (\Exception $e) {
            $message = $e->getMessage();

            return redirect()->back()->withError($message);
        }

        $links = countInternalExternalLinks($contents, $domain);
        $content = get_meta_tags_details($contents, array('title', 'description', 'keywords', 'viewport', 'robots', 'author', 'og:type'));
        $content['size'] = $pageSize;
        $content['internal_links'] = $links['internal']->count();

        $results = [
            'domain' => $request->domain,
            'content' => $content
        ];

        return view('tools.meta-tag-analyzer', compact('results', 'tool'));
    }
}
