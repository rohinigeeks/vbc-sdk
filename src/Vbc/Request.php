<?php

namespace Vbc;

use Guzzlehttp;

class Request
{

    protected $connection;

    public function __construct(Client $client)
    {
        $this->client = $client->getConnection();
    }

    public static function factory($config)
    {
        $client = new Client($config);

        return new Request($client);
    }

    public function start($data = [], $options = [])
    {
        $dataKeys = ['uid', 'data', 'caption', 'callback'];
        $optionKeys = ['splash', 'pin', 'auto'];
        
        $payload = [];
        $query = [];

        foreach ($dataKeys as $key) {
            if (isset($data[$key])) {
                $payload[$key] = $data[$key];
            }
        }

        if (isset($options['splash'])) {
            $query['splash'] = $options['splash'];
        }

        if (isset($options['pin'])) {
            $query['pin'] = $options['pin'];
        }

        if (!isset($options['auto']) || $options['auto'] === true) {
            if ($this->client->isMobile) {
                $query['pin'] = true;
            }
        }

        try {
            $body = ['json' => $payload];

            if (!empty($query)) { 
                $body['query'] = $query;
            }

            $response = $this->client->post('/request', $data);
            return $response->json();
        } catch (GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                // --
            }
            return null;
        }
    }

}