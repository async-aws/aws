<?php

declare(strict_types=1);

namespace AsyncAws\Sqs\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Sqs\SqsClient;

trait GetClient
{
    private function getClient(): SqsClient
    {
        return new SqsClient([
            'endpoint' => 'http://localhost:9494',
        ], new NullProvider());
    }
}
