<?php

namespace Vbc;

use GuzzleHttp;

class Client
{

    protected $client;

    public function __construct($config = [])
    {
        $version = (isset($config['Version'])) ? $config['Version']: 'v1';

        $this->client = new GuzzleHttp\Client([
            'base_url' => 'https://api.verifiedbycam.com/' . $version . '/',
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
