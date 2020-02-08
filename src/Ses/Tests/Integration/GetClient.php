<?php

declare(strict_types=1);

namespace AsyncAws\Ses\Tests\Integration;

use AsyncAws\Ses\SesClient;

trait GetClient
{
    private function getClient(): SesClient
    {
        return new SesClient([
            'endpoint' => 'http://localhost:9001',
        ]);
    }
}
