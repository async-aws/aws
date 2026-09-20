<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\EndpointDiscovery\EndpointInterface;
use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Request;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Response;
use AsyncAws\Core\Stream\StringStream;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpClient\Retry\GenericRetryStrategy;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Component\HttpClient\TraceableHttpClient;
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
        if (method_exists(HttpClientInterface::class, 'withOptions')) {
            $httpClient->method('withOptions')->willReturnSelf();
        }
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

    public function testProvidedHttpClientRetriesThrottledCalls()
    {
        $attempts = 0;
        $httpClient = new MockHttpClient(static function () use (&$attempts): MockResponse {
            ++$attempts;

            return $attempts < 3
                ? new MockResponse('{"__type":"ThrottlingException"}', ['http_code' => 400])
                : new MockResponse('{}');
        });

        $api = new DummyApi([
            'region' => 'eu-west-1',
            'accessKeyId' => 'key',
            'accessKeySecret' => 'secret',
        ], null, $httpClient);

        $response = $api->getResponseExposed(new Request('GET', '/foo', [], [], StringStream::create('')));
        $response->resolve();

        self::assertSame(3, $attempts);
    }

    public function testProvidedHttpClientThatAlreadyRetriesDoesNotRetryTwice()
    {
        $attempts = 0;
        $throttled = new MockHttpClient(static function () use (&$attempts): MockResponse {
            ++$attempts;

            return new MockResponse('{"__type":"ThrottlingException"}', ['http_code' => 400]);
        });

        // a profiler puts a TraceableHttpClient above the retryable one, so the option has to reach it through the chain
        $httpClient = new TraceableHttpClient(new RetryableHttpClient($throttled, new GenericRetryStrategy([400], 1, 1.0, 0, 0.0), 3));

        $api = new DummyApi([
            'region' => 'eu-west-1',
            'accessKeyId' => 'key',
            'accessKeySecret' => 'secret',
        ], null, $httpClient);

        try {
            $api->getResponseExposed(new Request('GET', '/foo', [], [], StringStream::create('')))->resolve();
        } catch (ClientException) {
        }

        self::assertSame(4, $attempts);
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
