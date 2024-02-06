<?php

namespace App\Helpers\Classes;

use Exception;
use Carbon\Carbon;
use RuntimeException;

class CheckSSL
{

    protected array $urls;
    protected array $result;
    protected float $timeOut;

    /**
     * CheckSSL constructor.
     * @param array $url
     * @param float $timeOut
     * @throws Exception
     */
    public function __construct(array $url = [], float $timeOut = 30)
    {
        !empty($url) ? $this->add($url) : $this->urls = $url;
        $this->timeOut = $timeOut;
        $this->result = [];
    }

    /**
     * @param array $data
     * @return CheckSSL
     * @throws \Exception
     */
    public function add(...$data): CheckSSL
    {
        /** @var array|string $url */
        foreach ($data as $url) {
            if (is_iterable($url)) {
                foreach ($url as $i) {
                    $this->add($i);
                }
                continue;
            }

            if (empty($url)) {
                throw new \Exception('please  target url is empty');
            }

            if (!$this->isValidUrl($url)) {
                throw new \Exception('malformed URLs');
            }

            $cleanUrl = parse_url($url, PHP_URL_HOST);

            if ($cleanUrl === null) {
                throw new \Exception('seriously malformed URLs');
            }

            $this->urls[] = $cleanUrl;
        }
        return $this;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function check(): ?array
    {
        foreach ($this->urls as $item) {

            /** @var resource|false $cert */
            $cert = $this->getCert($item);

            if ($cert === false) {
                $this->result[$item] = null;
                continue;
            }

            $this->result[$item] =  $this->getSLLInformation($cert);
        }

        return $this->getResults();
    }

    public function getTimeout(): float
    {
        return $this->timeOut;
    }

    /**
     * @param resource|false $siteStream
     * @return array
     * @throws Exception
     */
    private function getSLLInformation($siteStream): array
    {
        try {
            if (!is_resource($siteStream) || get_resource_type($siteStream) !== 'stream') {
                throw new RuntimeException('param $siteStream not type stream');
            }
            $certStream = stream_context_get_params($siteStream);

            $cert = $this->getCertFromArray($certStream);

            openssl_x509_export($cert , $cert_chain , true);

             $certInfo = openssl_x509_parse($cert);

            $isValid = time() <= $certInfo['validTo_time_t'];
            $valid_from = Carbon::parse($certInfo['validFrom_time_t']);
            $valid_to   = Carbon::parse($certInfo['validTo_time_t']);
            $issuer = $isValid? ($certInfo['issuer']['O'] ?? __('seo.selfSigned')) : false;
        } catch (Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }

        return [
            'is_valid'    => $isValid,
            'issuer'    => $issuer,
            'created_at'  => $valid_from,
            'expire_at' => $valid_to,
            'certInfo' => $certInfo,
            'cert_chain' => $cert_chain,
        ];
    }

    /**
     * @return array|mixed
     */
    private function getResults()
    {
        if (count($this->result) === 1) {
            return current($this->result);
        }

        return $this->result;
    }

    /**
     * @return resource
     */
    private function getStreamContext()
    {
        return stream_context_create(
            [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'capture_peer_cert' => true
                ]
            ]
        );
    }

    /**
     * @param string $url
     * @return false|resource
     */
    private function getCert(string $url)
    {
        try {
            $messageError = 'error to get certificate';
            $cert = @stream_socket_client(
                'ssl://' . $url . ':443',
                $errno,
                $messageError,
                $this->timeOut,
                STREAM_CLIENT_CONNECT,
                $this->getStreamContext()
            );
        } catch (\Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }

        return  $cert;
    }

    /**
     * @param array $certStream
     *
     * @return \OpenSSLCertificate
     */
    private function getCertFromArray(array $certStream)
    {
        return $certStream['options']['ssl']['peer_certificate'];
    }

    /**
     * @param string $data
     * @return bool
     */
    private function isValidUrl(string $data): bool
    {
        $regex =
            "%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*" .
            "[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*" .
            "(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu";

        return (1 === preg_match($regex, $data));
    }
}
