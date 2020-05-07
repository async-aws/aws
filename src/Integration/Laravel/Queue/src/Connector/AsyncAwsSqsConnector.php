<?php

namespace AsyncAws\Illuminate\Queue\Connector;

use AsyncAws\Illuminate\Queue\AsyncAwsSqsQueue;
use AsyncAws\Sqs\SqsClient;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Queue\Connectors\ConnectorInterface;
use Symfony\Component\HttpClient\HttpClient;

class AsyncAwsSqsConnector implements ConnectorInterface
{
    /**
     * Establish a queue connection.
     *
     * @return Queue
     */
    public function connect(array $config)
    {
        $clientConfig = [];
        if ($config['key'] && $config['secret']) {
            $clientConfig['accessKeyId'] = $config['key'] ?? null;
            $clientConfig['accessKeySecret'] = $config['secret'] ?? null;
            $clientConfig['sessionToken'] = $config['token'] ?? null;
        }

        return new AsyncAwsSqsQueue(
            new SqsClient($clientConfig, null, HttpClient::create(['timeout' => 30])),
            $config['queue'],
            $config['prefix'] ?? '',
            $config['suffix'] ?? ''
        );
    }
}
