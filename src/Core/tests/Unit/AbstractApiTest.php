<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\EndpointDiscovery\EndpointInterface;
use AsyncAws\Core\Request;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Response;
use AsyncAws\Core\Stream\StringStream;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

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

    public function testDiscoveredEndpoint()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $api = new DummyApi([
            'region' => 'eu-west-1',
            'accessKeyId' => 'key',
            'accessKeySecret' => 'secret',
        ], null, $httpClient);

        $response = $this->createMock(ResponseInterface::class);

        $httpClient->expects(self::once())
            ->method('request')
            ->with('GET', 'https://foobar.discovered.amazonaws.com/foo')
            ->willReturn($response)
        ;

        $response = $api->getResponseExposed(new Request('GET', '/foo', [], [], StringStream::create('')), new RequestContext(['requiresEndpointDiscovery' => true]));
        $response->cancel();
    }

    public function testGetResponseDoesNotDisableBufferingByDefault(): void
    {
        $httpClient = new MockHttpClient(static function (string $method, string $url, array $options): ResponseInterface {
            self::assertNotSame(false, $options['buffer'] ?? null);

            return new SimpleMockedResponse('OK');
        });
        $api = new DummyApi([], new NullProvider(), $httpClient);

        $response = $api->getResponseExposed(new Request('GET', '/foo', [], [], StringStream::create('')), new RequestContext());

        self::assertTrue($response->resolve());
    }

    public function testGetResponseCanDisableBuffering(): void
    {
        $httpClient = new MockHttpClient(static function (string $method, string $url, array $options): ResponseInterface {
            self::assertSame(false, $options['buffer'] ?? null);

            return new SimpleMockedResponse('OK');
        });
        $api = new DummyApi([], new NullProvider(), $httpClient);

        $response = $api->getResponseExposed(new Request('GET', '/foo', [], [], StringStream::create('')), new RequestContext(['responseBuffer' => false]));

        self::assertTrue($response->resolve());
    }
}

class DummyApi extends AbstractApi
{
    public function getEndpoint(string $uri, array $query, ?string $region): string
    {
        return parent::getEndpoint($uri, $query, $region);
    }

    public function getResponseExposed(Request $request, ?RequestContext $context = null): Response
    {
        return parent::getResponse($request, $context);
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

    protected function discoverEndpoints(?string $region): array
    {
        return [new class implements EndpointInterface {
            public function getAddress(): string
            {
                return 'https://foobar.discovered.amazonaws.com';
            }

            public function getCachePeriodInMinutes(): int
            {
                return 3600;
            }
        }];
    }
}
