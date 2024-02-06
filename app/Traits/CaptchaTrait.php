<?php

namespace App\Traits;

use ReCaptcha\ReCaptcha;

trait CaptchaTrait
{
    public function captchaCheck($page_status)
    {
        if (!\Setting::get('recaptcha_status', 0) || !$page_status) {
            return true;
        }

        $secret = \Setting::get('recaptcha_secret', false);
        if (empty($secret)) {
            return true;
        }

        $request = request();
        $response = $request->get('g-recaptcha-response', false);
        $remoteip = $request->getClientIp();
        $hostname = $request->getHost();

        $recaptcha = new ReCaptcha($secret);
        $resp = $recaptcha->setExpectedHostname($hostname)->verify($response, $remoteip);

        if ($resp->isSuccess()) {
            return true;
        }

        return false;
    }
}
