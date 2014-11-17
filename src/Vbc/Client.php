<?php

namespace Vbc;

use GuzzleHttp;

class Client
{

    protected $client;

    public function __construct($config = [])
    {
        $config['Host'] = isset($config['Host']) ? $config['Host']: 'https://api.verifiedbycam.com/v2/';
        $this->client = new GuzzleHttp\Client([
            'base_url' => $config['Host'],
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
