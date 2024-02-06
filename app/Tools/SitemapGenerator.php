<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Support\Str;
use Spatie\Crawler\Crawler;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\SitemapGenerator as Sitemap;

class SitemapGenerator implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.sitemap-generator', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'url' => 'required|url'
        ]);
        set_time_limit(0);
        try {
            Artisan::call('optimize:clear');
            $url = $request->input('url');
            $hostname = extractHostname($url, true);
            $process_id = Str::uuid()->toString();
            $disk = config('artisan.public_files_disk', 'public');

            $file = Cache::remember($process_id, job_cache_time(), function () use ($hostname, $process_id) {
                $path = config('artisan.temporary_files_path', 'temp') . '/' . $process_id . '/';

                return $path . $hostname . '.xml';
            });

            $sitemap = Sitemap::create($url)
                ->configureCrawler(function (Crawler $crawler) {
                    $crawler->rejectNofollowLinks();
                    $crawler->respectRobots();
                    $crawler->setMaximumDepth(1);
                })
                ->getSitemap();
            $sitemap->writeToDisk($disk, $file);

            $sitemap = url(Storage::disk($disk)->url($file));
            $content = Storage::disk($disk)->get($file);
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }

        $results = [
            'url' => $url,
            'hostname' => $hostname,
            'process_id' => $process_id,
            'download_url' => $sitemap,
            'content' => $content,
        ];

        return view('tools.sitemap-generator', compact('results', 'tool'));
    }

    public function postAction(Request $request, $tool)
    {
        Artisan::call('optimize');

        $process_id = $request->process_id;
        // Get last cached resource
        $file = Cache::get($process_id);

        if (!$file) {
            return response()->json(['status' => false, 'message' => __('tools.theRequestExpired')]);
        }

        $disk = config('artisan.public_files_disk', 'public');

        // Make path for all images
        $xmlData = Storage::disk($disk)->get($file);

        return response($xmlData)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="sitemap.xml"');
    }
}
