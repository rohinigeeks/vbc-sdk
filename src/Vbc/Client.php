<?php

namespace Vbc;

use GuzzleHttp;

class Client
{

    protected $baseUrl = 'https://{subdomain}.verifiedbycam.com/{+version*}';

    protected $client;

    public function __construct($config = [])
    {
        $this->client = new GuzzleHttp\Client([
            'base_url' => [$this->baseUrl, ['subdomain' => 'api', 'version' => 'v1']],
            'defaults' => [
                'headers' => [
                    'X-App-Key' => $config['AppKey'],
                    'X-Secret-Key' => $config['SecretKey']
                ],
                'timeout' => 20
            ]
        ]);

        // disable this
        $this->client->setDefaultOption('verify', false);
    }

    public static function getInstance($config = [])
    {
        static $instance;
        if (get_class($instance) !== 'Client') {
            return new Client($config);
        }
        return $instance;
    }

    public function getConnection()
    {
        $this->client->isMobile = $this->isMobile($_SERVER["HTTP_USER_AGENT"]);
        return $this->client;
    }

    private function isMobile($ua) {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", strtolower($ua));
    }

}
