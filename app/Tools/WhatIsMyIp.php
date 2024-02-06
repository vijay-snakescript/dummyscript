<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;


class WhatIsMyIp implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        $ip = $this->getIp();
        $driver = (new ToolsManager($tool))->driver();
        $results = $driver->parse($ip);
        $content = $results['content'] ?? false;

        return view('tools.what-is-my-ip', compact('tool', 'content', 'ip'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $ip = $this->getIp();
        $driver = (new ToolsManager($tool))->driver();
        $results = $driver->parse($ip);
        $content = $results['content'] ?? false;

        if (!$content) {
            return redirect()->back()->withError(__('common.somethingWentWrong'));
        }

        return view('tools.what-is-my-ip', compact('results', 'content', 'tool', 'ip'));
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
                    'options' => [['text' => "IP Api", 'value' => "IpApi"], ['text' => "Free IP API", 'value' => "FreeIpApi"]],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ]
            ],
            "default" => ['driver' => 'IpApi']
        ];

        return $array;
    }

    public function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return the server IP if the client IP is not found using this method.
    }
}
