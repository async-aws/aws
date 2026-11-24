<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Credentials;

use AsyncAws\Core\Configuration;
use AsyncAws\Core\Credentials\ContainerProvider;
use AsyncAws\Core\Credentials\Credentials;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ContainerProviderTest extends TestCase
{
    private const FULL_URI = 'http://127.0.0.1/credentials';
    private const EXPIRATION = '2030-01-01T00:00:00Z';

    /**
     * @var list<array{url: string, authorization: string|null}>
     */
    private $requests = [];

    /**
     * @var string|null
     */
    private $tokenFile;

    protected function tearDown(): void
    {
        if (null !== $this->tokenFile && is_file($this->tokenFile)) {
            unlink($this->tokenFile);
        }
    }

    public function testPlainTokenIsSentAsAuthorizationHeader(): void
    {
        $credentials = $this->createProvider()->getCredentials(Configuration::create([
            'podIdentityCredentialsFullUri' => self::FULL_URI,
            'containerAuthorizationToken' => 'plain-token',
        ]));

        self::assertSame([['url' => self::FULL_URI, 'authorization' => 'plain-token']], $this->requests);
        self::assertCredentials($credentials);
    }

    public function testPlainTokenIsSentWithRelativeUri(): void
    {
        $credentials = $this->createProvider()->getCredentials(Configuration::create([
            'containerCredentialsRelativeUri' => '/v2/credentials/id',
            'containerAuthorizationToken' => 'plain-token',
        ]));

        self::assertSame([['url' => 'http://169.254.170.2/v2/credentials/id', 'authorization' => 'plain-token']], $this->requests);
        self::assertCredentials($credentials);
    }

    public function testTokenFileIsSentAsAuthorizationHeader(): void
    {
        $credentials = $this->createProvider()->getCredentials(Configuration::create([
            'podIdentityCredentialsFullUri' => self::FULL_URI,
            'podIdentityAuthorizationTokenFile' => $this->createTokenFile('file-token'),
        ]));

        self::assertSame([['url' => self::FULL_URI, 'authorization' => 'file-token']], $this->requests);
        self::assertCredentials($credentials);
    }

    public function testTokenFileTakesPrecedenceOverPlainToken(): void
    {
        $credentials = $this->createProvider()->getCredentials(Configuration::create([
            'podIdentityCredentialsFullUri' => self::FULL_URI,
            'podIdentityAuthorizationTokenFile' => $this->createTokenFile('file-token'),
            'containerAuthorizationToken' => 'plain-token',
        ]));

        self::assertSame([['url' => self::FULL_URI, 'authorization' => 'file-token']], $this->requests);
        self::assertCredentials($credentials);
    }

    public function testUnreadableTokenFileDoesNotFallBackToPlainToken(): void
    {
        $provider = $this->createProvider();
        $configuration = Configuration::create([
            'podIdentityCredentialsFullUri' => self::FULL_URI,
            'podIdentityAuthorizationTokenFile' => sys_get_temp_dir() . '/async-aws-missing-token-file-' . bin2hex(random_bytes(8)),
            'containerAuthorizationToken' => 'plain-token',
        ]);

        // reading a missing file triggers PHP warnings
        set_error_handler(static function (): bool {
            return true;
        });

        try {
            $credentials = $provider->getCredentials($configuration);
        } finally {
            restore_error_handler();
        }

        self::assertNull($credentials);
        self::assertSame([], $this->requests);
    }

    public function testNoAuthorizationHeaderWithoutToken(): void
    {
        $credentials = $this->createProvider()->getCredentials(Configuration::create([
            'podIdentityCredentialsFullUri' => self::FULL_URI,
        ]));

        self::assertSame([['url' => self::FULL_URI, 'authorization' => null]], $this->requests);
        self::assertCredentials($credentials);
    }

    public function testNoAuthorizationHeaderWithEmptyToken(): void
    {
        $credentials = $this->createProvider()->getCredentials(Configuration::create([
            'podIdentityCredentialsFullUri' => self::FULL_URI,
            'containerAuthorizationToken' => '',
        ]));

        self::assertSame([['url' => self::FULL_URI, 'authorization' => null]], $this->requests);
        self::assertCredentials($credentials);
    }

    #[DataProvider('provideInvalidTokens')]
    public function testTokenWithLineBreakIsRejected(string $token, bool $fromFile): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())
            ->method('warning')
            ->with(self::logicalNot(self::stringContains('secret')), []);
        $logger->expects(self::never())->method('info');

        $options = ['podIdentityCredentialsFullUri' => self::FULL_URI];
        if ($fromFile) {
            $options['podIdentityAuthorizationTokenFile'] = $this->createTokenFile($token);
        } else {
            $options['containerAuthorizationToken'] = $token;
        }

        $credentials = $this->createProvider($logger)->getCredentials(Configuration::create($options));

        self::assertNull($credentials);
        self::assertSame([], $this->requests);
    }

    public static function provideInvalidTokens(): iterable
    {
        yield 'plain token with LF' => ["secret\n", false];
        yield 'plain token with CR LF' => ["secret\r\nX-Injected: value", false];
        yield 'plain token with CR' => ["secret\rvalue", false];
        yield 'plain token with NUL' => ["secret\0value", false];
        yield 'token file with trailing LF' => ["secret\n", true];
    }

    private function createProvider(?LoggerInterface $logger = null): ContainerProvider
    {
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options): MockResponse {
            $this->requests[] = [
                'url' => $url,
                'authorization' => isset($options['normalized_headers']['authorization'][0]) ? substr($options['normalized_headers']['authorization'][0], \strlen('Authorization: ')) : null,
            ];

            return new MockResponse(json_encode([
                'AccessKeyId' => 'key-id',
                'SecretAccessKey' => 'secret-key',
                'Token' => 'session-token',
                'Expiration' => self::EXPIRATION,
            ]));
        });

        return new ContainerProvider($httpClient, $logger);
    }

    private function createTokenFile(string $token): string
    {
        $this->tokenFile = tempnam(sys_get_temp_dir(), 'async-aws-token-');
        file_put_contents($this->tokenFile, $token);

        return $this->tokenFile;
    }

    private static function assertCredentials(?Credentials $credentials): void
    {
        self::assertNotNull($credentials);
        self::assertSame('key-id', $credentials->getAccessKeyId());
        self::assertSame('secret-key', $credentials->getSecretKey());
        self::assertSame('session-token', $credentials->getSessionToken());
        self::assertEquals(Credentials::adjustExpireDate(new \DateTimeImmutable(self::EXPIRATION)), $credentials->getExpireDate());
    }
}
