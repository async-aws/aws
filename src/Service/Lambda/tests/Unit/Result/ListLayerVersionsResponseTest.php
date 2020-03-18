<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Result\LayerVersionsListItem;
use AsyncAws\Lambda\Result\ListLayerVersionsResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class ListLayerVersionsResponseTest extends TestCase
{
    public function testListLayerVersionsResponse(): void
    {
        $nextMarker = 'sO+mEQJefBjPIeg+l6HFuqdd23/x2PrL0ZwEjDQs0xfDZhrv3ui/5CYVmYoHOKZdMvDx8fOc+tXs7rlKDBbcMSu2UkKbxqoMVxN4i4OIXlia4Fj2iUPqDJBALx9KbD9IasyH4qHLxhjh93kUVM/W8NM22fbC6IzgRB6xtz5FAhuGYZnfow496/4qaDkJwdvyiOHOZw3lg9f+DwRNTo28pc6lOqfbYAFal/lpd9WKl0kW6m968/gexL6B2biH7u0PKNxN2MOvEO/tSv5s7QLWztvKTYsp2IfVDwmOyvwGVxefZOA8r5o27XBKQzCOZkOxwhxbcPJ6Tzn4J8Othvf7lJMOFZpMOlOCqn5e/V/IPiykaDtf3KshDii5tkliF7DA';
        $data = [
            'LayerVersions' => [
                0 => [
                    'CompatibleRuntimes' => [
                        0 => 'provided',
                    ],
                    'CreatedDate' => '2020-02-22T15:34:34.958+0000',
                    'Description' => 'amqp-php-72',
                    'LayerVersionArn' => 'arn:aws:lambda:eu-central-1:403367587399:layer:amqp-php-72:2',
                    'LicenseInfo' => 'MIT',
                    'Version' => 2,
                ],
            ],
            'NextMarker' => $nextMarker,
        ];

        $response = new SimpleMockedResponse(json_encode($data));

        $client = new MockHttpClient($response);
        $result = new ListLayerVersionsResponse(new Response($client->request('POST', 'http://localhost'), $client));

        self::assertEquals($nextMarker, $result->getNextMarker());
        /** @var LayerVersionsListItem $version */
        foreach ($result->getLayerVersions(true) as $version) {
            self::assertEquals(2, $version->getVersion());
            self::assertEquals(
                'arn:aws:lambda:eu-central-1:403367587399:layer:amqp-php-72:2',
                $version->getLayerVersionArn()
            );
            self::assertEquals('amqp-php-72', $version->getDescription());
            self::assertEquals('MIT', $version->getLicenseInfo());
            self::assertEquals([0 => 'provided'], $version->getCompatibleRuntimes());
        }
    }
}
