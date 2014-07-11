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
        $defaults = [
            'status' => null,
            'uid' => null,
            'page' => 1,
            'size' => 20
        ];
        
        $options = array_merge($defaults, $options);

        try {
            $response = $this->client->get('v1/requests/', [
                'query' => $options
            ]);
            $items = $response->json();

            return [
                'total' => $items['total'],
                'size' => $items['size'],
                'page' => $items['page'],
                'requests' => new GuzzleHttp\Collection($items['requests'])
            ];
        } catch (GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse();
            }
            return null;
        }
    }

    public function verify($requestId, $options = [])
    {
        $this->updateStatus($requestId, 'VERIFIED', $options);
    }

    public function reject($requestId, $options = [])
    {
        $this->updateStatus($requestId, 'REJECTED', $options);
    }

    protected function updateStatus($requestId, $status = 'REJECTED', $options = [])
    {
        $defaults = [
            'reason' => null,
            'reviewer' => null
        ];
        
        $options = array_merge($defaults, $options);
        
        $payload = [
            'status' => $status,
            'reason' => $options['reason'],
            'reviewer' => $options['reviewer']
        ];

        try {
            $response = $this->client->put('v1/request/' . $requestId, [
                'json' => $payload
            ]);
            return $response->json();
        } catch (GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse();
            }
            return null;
        }
    }

}
