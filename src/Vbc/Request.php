<?php

namespace Vbc;

use GuzzleHttp;

class Request
{
    protected $data;

    protected $connection;

    public function __construct(Client $client)
    {
        $this->client = $client->getConnection();   
    }

    public static function factory($config)
    {
        $client = Client::getInstance($config);
        return new Request($client);
    }

    public function create($data = [], $options = [])
    {
        $dataKeys = ['uid', 'data', 'caption', 'callback'];
        
        $payload = [];
        $query = [];

        foreach ($dataKeys as $key) {
            if (isset($data[$key])) {
                $payload[$key] = $data[$key];
            }
        }

        if (!isset($options['auto'])) {
            $options['auto'] = true;
        }

        if ($options['auto'] === true) {
            if (!$this->client->isMobile) {
                $query['pin'] = "true";
            }
        }

        if (isset($options['pin'])) {
            $query['pin'] = $options['pin'] ? "true": "";
        }

        try {
            $body = ['json' => $payload];

            if (!empty($query)) { 
                $body['query'] = $query;
            }

            $response = $this->client->post('v1/request', $body);
            return $response->json();
        } catch (GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                // --
            }
            return null;
        }
    }

    public function all($options = [])
    {
        $optionDefaults = [
            'uid' => null,
            'status' => null,
            'sort' => 'date',
            'order' => 'desc',
            'size' => 20
        ];

        try {
            $response = $this->client->get('v1/requests/');
            $items = $response->json();
            $requests = [];

            foreach ($items as $item) {
                $requests[] = new GuzzleHttp\Collection($item);
            }

            return $requests;
        } catch (GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                // --
            }
            return null;
        }
    }

    public function verify($requestId)
    {
        $this->updateStatus($requestId, 'VERIFIED');
    }

    public function reject($requestId, String $reason)
    {
        $this->updateStatus($requestId, 'REJECTED', $reason);
    }

    protected function updateStatus($requestId, $status = 'REJECTED', $reason = null)
    {
        $payload = [
            'status' => $status,
            'reason' => $reason
        ];

        try {
            $response = $this->client->put('v1/request/' . $requestId, [
                'json' => $payload
            ]);
            return $response->json();
        } catch (GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                // --
            }
            return null;
        }
    }

}
