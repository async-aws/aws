<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Configuration;
use PHPUnit\Framework\TestCase;

class AbstractApiTest extends TestCase
{
    public function testGetEndpointRegion()
    {
        $api = new DummyApi();

        // Use default region
        $endpoint = $api->getEndpoint('/some/path', [], null);
        self::assertEquals('https://foobar.us-east-1.amazonaws.com/some/path', $endpoint);

        $endpoint = $api->getEndpoint('/some/path', [], 'eu-central-1');
        self::assertEquals('https://foobar.eu-central-1.amazonaws.com/some/path', $endpoint);

        // Use region from config
        $api = new DummyApi(['region' => 'eu-north-1']);
        $endpoint = $api->getEndpoint('/some/path', [], null);
        self::assertEquals('https://foobar.eu-north-1.amazonaws.com/some/path', $endpoint);

        $endpoint = $api->getEndpoint('/some/path', [], 'eu-central-1');
        self::assertEquals('https://foobar.eu-central-1.amazonaws.com/some/path', $endpoint);
    }
}

class DummyApi extends AbstractApi
{
    public function getEndpoint(string $uri, array $query, ?string $region): string
    {
        return parent::getEndpoint($uri, $query, $region);
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        return [
            'endpoint' => "https://foobar.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'foobar',
            'signVersions' => ['v4'],
        ];
    }
}
