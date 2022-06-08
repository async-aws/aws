<?php

namespace AsyncAws\Comprehend\Tests\Integration;

use AsyncAws\Comprehend\ComprehendClient;
use AsyncAws\Comprehend\Input\DetectDominantLanguageRequest;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class ComprehendClientTest extends TestCase
{
    public function testDetectDominantLanguage(): void
    {
        $client = $this->getClient();

        $input = new DetectDominantLanguageRequest([
            'Text' => 'change me',
        ]);
        $result = $client->detectDominantLanguage($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getLanguages());
    }

    private function getClient(): ComprehendClient
    {
        self::markTestSkipped('There is no docker image available for Comprehend.');

        return new ComprehendClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
