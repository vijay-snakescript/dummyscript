<?php

namespace App\Tools;

use App\Models\Tool;
use Iodev\Whois\Factory;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;


class DomainHostingChecker implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.domain-hosting-checker', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'domain' => 'required|fqdn',
        ]);

        $whois = Factory::get()->createWhois();
        $domain = extractHostname($request->domain, true);
        $hostname = extractHostname($request->domain);
        $info = $whois->loadDomainInfo($domain);

        $driver = (new ToolsManager($tool))->channel("ipApi");
        $content = $driver->parse($hostname);

        $results = [
            'info' => $info,
            'domain' => $request->domain,
            'content' => $content
        ];

        return view('tools.domain-hosting-checker', compact('results', 'tool'));
    }

    public static function getProperties()
    {
        $properties = ['Daily Usage', 'Word Count'];

        return $properties;
    }

    public function parseComments($contents, $meta_list)
    {
        $file_data = str_replace("\r", "\n", $contents);
        $all_headers = $meta_list;

        foreach ($all_headers as $field => $regex) {
            if (
                preg_match('/^[ \t\/*#@]*' . preg_quote($regex, '/') . ':(.*)$/mi', $file_data, $match)
                && $match[1]
            )
                $all_headers[$field] = trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', $match[1]));
            else
                $all_headers[$field] = '';
        }

        return $all_headers;
    }

    public static function getFileds()
    {
        $array = [
            'title' => "Drivers",
            'fields' => [
                [
                    'id' => "driver",
                    'field' => "tool-options-select",
                    'placeholder' => "Driver",
                    'label' => "Driver",
                    'required' => true,
                    'options' => [['text' => "Default", 'value' => "WhoisDomainChecker"]],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ],
            ],
            "default" => ['driver' => 'WhoisDomainChecker']
        ];

        return $array;
    }
}
