<?php

namespace App\Components\Drivers;

use Carbon\Carbon;
use Iodev\Whois\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Contracts\ToolDriverInterface;

class WhoisDomainChecker implements ToolDriverInterface
{
    public function parse($domain)
    {
        $cacheKey = (empty($domain) ? (string) Str::uuid() : Str::slug($domain)) . '-whois';
        $info = Cache::rememberForever($cacheKey, function () use ($domain) {
            $host = extractHostname($domain, true);

            $whois = Factory::get()->createWhois();

            return $whois->loadDomainInfo($host);
        });

        $content = $this->results($info);

        return compact('domain', 'content');
    }

    public function nameAvaiability($domain)
    {
        $msg  = "<span class='text-danger'>Registered</span>";
        $cacheKey = empty($domain) ? (string) Str::uuid() : Str::slug($domain);
        $content = Cache::rememberForever($cacheKey, function () use ($domain, $msg) {
            $whois = Factory::get()->createWhois();
            if ($whois->isDomainAvailable($domain)) {
                $msg = "<span class='text-success'>Available</span>";
            }

            return $this->resultsName($msg, $domain);
        });

        return compact('domain', 'content');
    }

    protected function results($info)
    {
        $expire_date = Carbon::parse($info->expirationDate);
        $registered_date = Carbon::parse($info->creationDate);

        $domain_age = $registered_date->diffInYears(now());

        return  [
            'expiry' => $expire_date->format('Y-m-d'),
            'created' => $registered_date->format('Y-m-d'),
            'age' => trans_choice('tools.domainYearsOld', $domain_age, ['year' => $domain_age]),
            'domain' => $info->domainName,
        ];
    }

    protected function resultsName($msg, $domain)
    {
        return  [
            'status' => $msg,
            'domain' => $domain,
        ];
    }
}
