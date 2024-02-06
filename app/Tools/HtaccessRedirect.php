<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;


class HtaccessRedirect implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        $type = 1;
        return view('tools.htaccess-redirect', compact('tool', 'type'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'domain' => 'required|fqdn',
            'type' => 'required',
        ]);

        $content = "";
        $type = $request->type;
        $domain = $request->domain;
        $hostname = extractHostname($domain);

        if ($request->type == 1) {
            $content = "RewriteEngine On
RewriteCond %{HTTP_HOST} ^{$hostname} [NC]
RewriteRule ^(.*)$ http://www.{$hostname}/$1 [L,R=301]";
        } else {
            $content = "RewriteEngine On
RewriteCond %{HTTP_HOST} ^www.{$hostname} [NC]
RewriteRule ^(.*)$ http://{$hostname}/$1 [L,R=301]";
        }

        $results = [
            'domain' => $request->domain,
            'hostname' => $hostname,
            'content' => $content,
        ];

        return view('tools.htaccess-redirect', compact('results', 'tool', 'type'));
    }

    public static function getProperties()
    {
        $properties = ['Daily Usage'];

        return $properties;
    }
}
