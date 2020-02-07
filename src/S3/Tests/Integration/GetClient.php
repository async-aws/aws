<?php

declare(strict_types=1);

namespace AsyncAws\S3\Tests\Integration;

use AsyncAws\S3\Input\PutObjectRequest;
use AsyncAws\S3\S3Client;
use PHPUnit\Framework\TestCase;

trait GetClient
{
    private function getClient(): S3Client
    {
        return new S3Client([
            'endpoint' => 'http://localhost:4569'
        ]);
    }
}
