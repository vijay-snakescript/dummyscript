<?php

namespace App\Http\Controllers;

use App\Components\Drivers\QPDFDriver;
use Illuminate\Http\Request;

class TestController extends Controller
{

    public function test()
    {
        $pdf = QPDFDriver::createInstance()
        ->source('D:\wamp\www\dotartisan\seotools\public\file.pdf')
        ->getVersion();
        dd($pdf);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNames()
    {
        $string = '/* Icons */
.an-age-calculator:after {
    content: "\f101";
}


.an-age-calculator:before {
    content: "\f102";
}


.an-area-converter:after {
    content: "\f103";
}


.an-area-converter:before {
    content: "\f104";
}


.an-article-rewriter:after {
    content: "\f105";
}


.an-article-rewriter:before {
    content: "\f106";
}


.an-ascii-to-binary:after {
    content: "\f107";
}


.an-ascii-to-binary:before {
    content: "\f108";
}


.an-average-calculator:after {
    content: "\f109";
}


.an-average-calculator:before {
    content: "\f10a";
}


.an-base64-encode-decode:after {
    content: "\f10b";
}


.an-base64-encode-decode:before {
    content: "\f10c";
}


.an-binary-to-ascii:after {
    content: "\f10d";
}


.an-binary-to-ascii:before {
    content: "\f10e";
}


.an-binary-to-decimal:after {
    content: "\f10f";
}


.an-binary-to-decimal:before {
    content: "\f110";
}


.an-binary-to-hex:after {
    content: "\f111";
}


.an-binary-to-hex:before {
    content: "\f112";
}


.an-binary-to-text:after {
    content: "\f113";
}


.an-binary-to-text:before {
    content: "\f114";
}


.an-black-list-check:after {
    content: "\f115";
}


.an-black-list-check:before {
    content: "\f116";
}


.an-byte-bit-converter:after {
    content: "\f117";
}


.an-byte-bit-converter:before {
    content: "\f118";
}


.an-convert-jpg:after {
    content: "\f119";
}


.an-convert-jpg:before {
    content: "\f11a";
}


.an-css-minifier:after {
    content: "\f11b";
}


.an-css-minifier:before {
    content: "\f11c";
}


.an-decimal-to-binary:after {
    content: "\f11d";
}


.an-decimal-to-binary:before {
    content: "\f11e";
}


.an-decimal-to-hex:after {
    content: "\f11f";
}


.an-decimal-to-hex:before {
    content: "\f120";
}


.an-discount-calculator:after {
    content: "\f121";
}


.an-discount-calculator:before {
    content: "\f122";
}


.an-domain-age-checker:after {
    content: "\f123";
}


.an-domain-age-checker:before {
    content: "\f124";
}


.an-domain-authority-checker:after {
    content: "\f125";
}


.an-domain-authority-checker:before {
    content: "\f126";
}


.an-domain-hosting-checker:after {
    content: "\f127";
}


.an-domain-hosting-checker:before {
    content: "\f128";
}


.an-domain-name-search:after {
    content: "\f129";
}


.an-domain-name-search:before {
    content: "\f12a";
}


.an-domain-to-ip:after {
    content: "\f12b";
}


.an-domain-to-ip:before {
    content: "\f12c";
}


.an-electric-voltage-converter:after {
    content: "\f12d";
}


.an-electric-voltage-converter:before {
    content: "\f12e";
}


.an-favicon-generator:after {
    content: "\f12f";
}


.an-favicon-generator:before {
    content: "\f130";
}


.an-find-dns-record:after {
    content: "\f131";
}


.an-find-dns-record:before {
    content: "\f132";
}


.an-grammar-check:after {
    content: "\f133";
}


.an-grammar-check:before {
    content: "\f134";
}


.an-hex-to-binary:after {
    content: "\f135";
}


.an-hex-to-binary:before {
    content: "\f136";
}


.an-html-editor:after {
    content: "\f137";
}


.an-html-editor:before {
    content: "\f138";
}


.an-html-minifier:after {
    content: "\f139";
}


.an-html-minifier:before {
    content: "\f13a";
}


.an-image-compressor:after {
    content: "\f13b";
}


.an-image-compressor:before {
    content: "\f13c";
}


.an-image-crop:after {
    content: "\f13d";
}


.an-image-crop:before {
    content: "\f13e";
}


.an-image-editor:after {
    content: "\f13f";
}


.an-image-editor:before {
    content: "\f140";
}


.an-image-resizer:after {
    content: "\f141";
}


.an-image-resizer:before {
    content: "\f142";
}


.an-img-text:after {
    content: "\f143";
}


.an-img-text:before {
    content: "\f144";
}


.an-img-word:after {
    content: "\f145";
}


.an-img-word:before {
    content: "\f146";
}


.an-ip-loaction:after {
    content: "\f147";
}


.an-ip-loaction:before {
    content: "\f148";
}


.an-javascript-minifier:after {
    content: "\f149";
}


.an-javascript-minifier:before {
    content: "\f14a";
}


.an-json-beautifier:after {
    content: "\f14b";
}


.an-json-beautifier:before {
    content: "\f14c";
}


.an-json-editor:after {
    content: "\f14d";
}


.an-json-editor:before {
    content: "\f14e";
}


.an-json-formatter:after {
    content: "\f14f";
}


.an-json-formatter:before {
    content: "\f150";
}


.an-json-to-xml:after {
    content: "\f151";
}


.an-json-to-xml:before {
    content: "\f152";
}


.an-json-validator:after {
    content: "\f153";
}


.an-json-validator:before {
    content: "\f154";
}


.an-json-viewer:after {
    content: "\f155";
}


.an-json-viewer:before {
    content: "\f156";
}


.an-length-converter:after {
    content: "\f157";
}


.an-length-converter:before {
    content: "\f158";
}


.an-md5-generator:after {
    content: "\f159";
}


.an-md5-generator:before {
    content: "\f15a";
}


.an-meme-generator:after {
    content: "\f15b";
}


.an-meme-generator:before {
    content: "\f15c";
}


.an-meta-tag-analyzer:after {
    content: "\f15d";
}


.an-meta-tag-analyzer:before {
    content: "\f15e";
}


.an-my-ip:after {
    content: "\f15f";
}


.an-my-ip:before {
    content: "\f160";
}


.an-online-html-viewer:after {
    content: "\f161";
}


.an-online-html-viewer:before {
    content: "\f162";
}


.an-online-png:after {
    content: "\f163";
}


.an-online-png:before {
    content: "\f164";
}


.an-online-text-editor:after {
    content: "\f165";
}


.an-online-text-editor:before {
    content: "\f166";
}


.an-open-graph-generator:after {
    content: "\f167";
}


.an-open-graph-generator:before {
    content: "\f168";
}


.an-paraphrasing-tool:after {
    content: "\f169";
}


.an-paraphrasing-tool:before {
    content: "\f16a";
}


.an-password-generator:after {
    content: "\f16b";
}


.an-password-generator:before {
    content: "\f16c";
}


.an-percentage-calculator:after {
    content: "\f16d";
}


.an-percentage-calculator:before {
    content: "\f16e";
}


.an-ping-tool:after {
    content: "\f16f";
}


.an-ping-tool:before {
    content: "\f170";
}


.an-plagiarism-checker:after {
    content: "\f171";
}


.an-plagiarism-checker:before {
    content: "\f172";
}


.an-power-converter:after {
    content: "\f173";
}


.an-power-converter:before {
    content: "\f174";
}


.an-pressure-converter:after {
    content: "\f175";
}


.an-pressure-converter:before {
    content: "\f176";
}


.an-probability-calculator:after {
    content: "\f177";
}


.an-probability-calculator:before {
    content: "\f178";
}


.an-qr-code-generator:after {
    content: "\f179";
}


.an-qr-code-generator:before {
    content: "\f17a";
}


.an-reverse-image-search:after {
    content: "\f17b";
}


.an-reverse-image-search:before {
    content: "\f17c";
}


.an-reverse-text-generator:after {
    content: "\f17d";
}


.an-reverse-text-generator:before {
    content: "\f17e";
}


.an-rgb-hex:after {
    content: "\f17f";
}


.an-rgb-hex:before {
    content: "\f180";
}


.an-sales-tax-calculator:after {
    content: "\f181";
}


.an-sales-tax-calculator:before {
    content: "\f182";
}


.an-seo-report:after {
    content: "\f183";
}


.an-seo-report:before {
    content: "\f184";
}


.an-small-text-generator:after {
    content: "\f185";
}


.an-small-text-generator:before {
    content: "\f186";
}


.an-speed-converter:after {
    content: "\f187";
}


.an-speed-converter:before {
    content: "\f188";
}


.an-spell-checker:after {
    content: "\f189";
}


.an-spell-checker:before {
    content: "\f18a";
}


.an-strength-checker:after {
    content: "\f18b";
}


.an-strength-checker:before {
    content: "\f18c";
}


.an-tag-generator:after {
    content: "\f18d";
}


.an-tag-generator:before {
    content: "\f18e";
}


.an-temperature-converter:after {
    content: "\f18f";
}


.an-temperature-converter:before {
    content: "\f190";
}


.an-text-to-ascii:after {
    content: "\f191";
}


.an-text-to-ascii:before {
    content: "\f192";
}


.an-text-to-binary:after {
    content: "\f193";
}


.an-text-to-binary:before {
    content: "\f194";
}


.an-text-to-image:after {
    content: "\f195";
}


.an-text-to-image:before {
    content: "\f196";
}


.an-text-to-speech:after {
    content: "\f197";
}


.an-text-to-speech:before {
    content: "\f198";
}


.an-time-converter:after {
    content: "\f199";
}


.an-time-converter:before {
    content: "\f19a";
}


.an-torque-converter:after {
    content: "\f19b";
}


.an-torque-converter:before {
    content: "\f19c";
}


.an-translate-english:after {
    content: "\f19d";
}


.an-translate-english:before {
    content: "\f19e";
}


.an-twitter-card-generator:after {
    content: "\f19f";
}


.an-twitter-card-generator:before {
    content: "\f1a0";
}


.an-uppercase-to-lowercase:after {
    content: "\f1a1";
}


.an-uppercase-to-lowercase:before {
    content: "\f1a2";
}


.an-url-encode-decode:after {
    content: "\f1a3";
}


.an-url-encode-decode:before {
    content: "\f1a4";
}


.an-url-opener:after {
    content: "\f1a5";
}


.an-url-opener:before {
    content: "\f1a6";
}


.an-volume-converter:after {
    content: "\f1a7";
}


.an-volume-converter:before {
    content: "\f1a8";
}


.an-website-screenshot:after {
    content: "\f1a9";
}


.an-website-screenshot:before {
    content: "\f1aa";
}


.an-weight-converter:after {
    content: "\f1ab";
}


.an-weight-converter:before {
    content: "\f1ac";
}


.an-word-combiner:after {
    content: "\f1ad";
}


.an-word-combiner:before {
    content: "\f1ae";
}


.an-word-counter:after {
    content: "\f1af";
}


.an-word-counter:before {
    content: "\f1b0";
}


.an-wp-generator:after {
    content: "\f1b1";
}


.an-wp-generator:before {
    content: "\f1b2";
}


.an-wp-theme-detector:after {
    content: "\f1b3";
}


.an-wp-theme-detector:before {
    content: "\f1b4";
}


.an-xml-formatter:after {
    content: "\f1b5";
}


.an-xml-formatter:before {
    content: "\f1b6";
}


.an-xml-to-json:after {
    content: "\f1b7";
}


.an-xml-to-json:before {
    content: "\f1b8";
}


.an-align-center:before {
    content: "\f1b9";
}


.an-align-left:before {
    content: "\f1ba";
}


.an-align-right:before {
    content: "\f1bb";
}


.an-angle-down:before {
    content: "\f1bc";
}


.an-arrow-alt-down:before {
    content: "\f1bd";
}


.an-attch-clip:before {
    content: "\f1be";
}


.an-balance:before {
    content: "\f1bf";
}


.an-bing:before {
    content: "\f1c0";
}


.an-bookmark:before {
    content: "\f1c1";
}


.an-card-visa:before {
    content: "\f1c2";
}


.an-card:before {
    content: "\f1c3";
}


.an-chack:before {
    content: "\f1c4";
}


.an-check-double:before {
    content: "\f1c5";
}


.an-circle-down-arrow:before {
    content: "\f1c6";
}


.an-circle:before {
    content: "\f1c7";
}


.an-copy-to-clipboard:before {
    content: "\f1c8";
}


.an-copy:before {
    content: "\f1c9";
}


.an-download:before {
    content: "\f1ca";
}


.an-dropbox:before {
    content: "\f1cb";
}


.an-email:before {
    content: "\f1cc";
}


.an-eye-slash:before {
    content: "\f1cd";
}


.an-eye:before {
    content: "\f1ce";
}


.an-facebook:before {
    content: "\f1cf";
}


.an-flip-horizontally:before {
    content: "\f1d0";
}


.an-flip-text:before {
    content: "\f1d1";
}


.an-flip-vertically:before {
    content: "\f1d2";
}


.an-flip-wording:before {
    content: "\f1d3";
}


.an-g-drive:before {
    content: "\f1d4";
}


.an-glob:before {
    content: "\f1d5";
}


.an-google:before {
    content: "\f1d6";
}


.an-heart:before {
    content: "\f1d7";
}


.an-historical-keywords:before {
    content: "\f1d8";
}


.an-image:before {
    content: "\f1d9";
}


.an-lcd:before {
    content: "\f1da";
}


.an-link:before {
    content: "\f1db";
}


.an-linkedin-in:before {
    content: "\f1dc";
}


.an-lock:before {
    content: "\f1dd";
}


.an-logo:before {
    content: "\f1de";
}


.an-long-arrow-down:before {
    content: "\f1df";
}


.an-long-arrow-up:before {
    content: "\f1e0";
}


.an-miscellaneous:before {
    content: "\f1e1";
}


.an-mobile:before {
    content: "\f1e2";
}


.an-moon:before {
    content: "\f1e3";
}


.an-overview:before {
    content: "\f1e4";
}


.an-paypal:before {
    content: "\f1e5";
}


.an-performance:before {
    content: "\f1e6";
}


.an-plug:before {
    content: "\f1e7";
}


.an-print:before {
    content: "\f1e8";
}


.an-reload:before {
    content: "\f1e9";
}


.an-resize:before {
    content: "\f1ea";
}


.an-resources:before {
    content: "\f1eb";
}


.an-reverse-each-words-lettering:before {
    content: "\f1ec";
}


.an-reverse-text:before {
    content: "\f1ed";
}


.an-reverse-wording:before {
    content: "\f1ee";
}


.an-rotate-left:before {
    content: "\f1ef";
}


.an-rotate:before {
    content: "\f1f0";
}


.an-search:before {
    content: "\f1f1";
}


.an-security:before {
    content: "\f1f2";
}


.an-seo-analysis:before {
    content: "\f1f3";
}


.an-settings:before {
    content: "\f1f4";
}


.an-sms:before {
    content: "\f1f5";
}


.an-square:before {
    content: "\f1f6";
}


.an-star:before {
    content: "\f1f7";
}


.an-stopwatch:before {
    content: "\f1f8";
}


.an-sun:before {
    content: "\f1f9";
}


.an-text:before {
    content: "\f1fa";
}


.an-times-circle:before {
    content: "\f1fb";
}


.an-triangle:before {
    content: "\f1fc";
}


.an-twitter:before {
    content: "\f1fd";
}


.an-upload-image:before {
    content: "\f1fe";
}


.an-upside-down:before {
    content: "\f1ff";
}


.an-vcard:before {
    content: "\f200";
}


.an-yandex:before {
    content: "\f201";
}


.an-instagram:before {
    content: "\f202";
}


.an-youtube:before {
    content: "\f203";
}
';
        preg_match_all('/an-(.*?):after/', $string, $match);
        dd($match[1]);
    }
}
