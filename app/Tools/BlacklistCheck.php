<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;

class BlacklistCheck implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.blacklist-check', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'domain' => 'required|fqdn',
        ]);

        $domain = $request->input('domain');
        $url = extractHostname($domain, true);
        $ip_address = dns_get_record($url, DNS_A);

        $results = [
            'domain' => $request->domain,
            'domainAddresses' => json_encode($this->dnsList()),
            'domainIp' => $ip_address[0]['ip'] ?? null
        ];

        return view('tools.blacklist-check', compact('results', 'tool'));
    }

    public function postAction(Request $request, $tool)
    {
        $domain = $request->input('domain');
        $ip = $request->input('search_ip');

        if ($ip) {
            $reverse_ip = implode(".", array_reverse(explode(".", $ip)));
            if (checkdnsrr($reverse_ip . "." . $domain . ".", "A")) {
                $listed['status'] = '<span class="text-danger">' . __('common.listed') . '</span>';
                $listed['host'] = $domain;
            } else {
                $listed['status'] = '<span class="text-success">' . __('common.notListed') . '</span>';
                $listed['host'] = $domain;
            }
        }

        $content = $listed ?? false;

        return $content;
    }


    public function dnsbllookup($ip)
    {
        $listed = [];
        $dnsbl_lookup = $this->dnsList(); // Add your preferred list of DNSBL's
        if ($ip) {
            $reverse_ip = implode(".", array_reverse(explode(".", $ip)));
            foreach ($dnsbl_lookup as $host) {
                if (checkdnsrr($reverse_ip . "." . $host . ".", "A")) {
                    $listed[$host] = '<span class="text-danger">Listed</span>';
                } else {
                    $listed[$host] = '<span color="text-success">Not-Listed</span>';
                }
            }
        }

        return $listed;
    }

    public function dnsList()
    {
        return array(
            "nsbl.justspam.org",
            "dnsbl.kempt.net",
            "dnsbl.madavi.de",
            "dnsbl.rizon.net",
            "dnsbl.rv-soft.info",
            "dnsbl-2.uceprotect.net",
            "dnsbl-3.uceprotect.net",
            "dnsbl.rymsho.ru",
            "dnsbl.sorbs.net",
            "dnsbl.zapbl.net",
            "dnsrbl.swinog.ch",
            "dul.pacifier.net",
            "dyn.nszones.com",
            "dyna.spamrats.com",
            "fnrbl.fast.net",
            "fresh.spameatingmonkey.net",
            "hostkarma.junkemailfilter.com",
            "images.rbl.msrbl.net",
            "ips.backscatterer.org",
            "ix.dnsbl.manitu.net",
            "korea.services.net",
            "l2.bbfh.ext.sorbs.net",
            "l3.bbfh.ext.sorbs.net",
            "l4.bbfh.ext.sorbs.net",
            "list.bbfh.org",
            "list.blogspambl.com",
            "mail-abuse.blacklist.jippg.org",
            "netbl.spameatingmonkey.net",
            "netscan.rbl.blockedservers.com",
            "no-more-funn.moensted.dk",
            "noptr.spamrats.com",
            "orvedb.aupads.org",
            "pbl.spamhaus.org",
            "phishing.rbl.msrbl.net",
            "pofon.foobar.hu",
            "psbl.surriel.com",
            "rbl.abuse.ro",
            "rbl.blockedservers.com",
            "rbl.dns-servicios.com",
            "rbl.efnet.org",
            "rbl.efnetrbl.org",
            "rbl.iprange.net",
            "rbl.schulte.org",
            "rbl.talkactive.net",
            "rbl2.triumf.ca",
            "rsbl.aupads.org",
            "sbl-xbl.spamhaus.org",
            "sbl.nszones.com",
            "sbl.spamhaus.org",
            "short.rbl.jp",
            "spam.dnsbl.anonmails.de",
            "spam.pedantic.org",
            "spam.rbl.blockedservers.com",
            "spam.rbl.msrbl.net",
            "spam.spamrats.com",
            "spamrbl.imp.ch",
            "spamsources.fabel.dk",
            "st.technovision.dk",
            "tor.dan.me.uk",
            "tor.dnsbl.sectoor.de",
            "tor.efnet.org",
            "torexit.dan.me.uk",
            "truncate.gbudb.net",
            "ubl.unsubscore.com",
            "uribl.spameatingmonkey.net",
            "urired.spameatingmonkey.net",
            "virbl.dnsbl.bit.nl",
            "virus.rbl.jp",
            "virus.rbl.msrbl.net",
            "vote.drbl.caravan.ru",
            "vote.drbl.gremlin.ru",
            "web.rbl.msrbl.net",
            "work.drbl.caravan.ru",
            "work.drbl.gremlin.ru",
            "wormrbl.imp.ch",
            "xbl.spamhaus.org",
            "zen.spamhaus.org",
            "dnsbl-1.uceprotect.net",
            "dnsbl.dronebl.org",
            "dnsbl.sorbs.net",
            "zen.spamhaus.org"
        );
    }
}
