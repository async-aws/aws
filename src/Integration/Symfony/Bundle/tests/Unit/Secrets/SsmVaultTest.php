<?php

namespace AsyncAws\Symfony\Bundle\Tests\Unit\Secrets;

use AsyncAws\Core\Test\ResultMockFactory;
use AsyncAws\Ssm\Result\GetParametersByPathResult;
use AsyncAws\Ssm\SsmClient;
use AsyncAws\Ssm\ValueObject\Parameter;
use AsyncAws\Symfony\Bundle\Secrets\SsmVault;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SsmVaultTest extends TestCase
{
    #[DataProvider('provideParameters')]
    public function testLoadEnvVars($path, $parameterName, $expected)
    {
        $client = $this->createMock(SsmClient::class);
        $ssmVault = new SsmVault($client, $path, true);

        $client->expects(self::once())
            ->method('getParametersByPath')
            ->willReturn(ResultMockFactory::create(GetParametersByPathResult::class, ['Parameters' => [new Parameter(['Name' => $parameterName, 'Value' => 'value'])]]));

        $actual = $ssmVault->loadEnvVars();

        self::assertEquals($expected, $actual);
    }

    public static function provideParameters(): iterable
    {
        yield 'simple' => [null, '/FOO', ['FOO' => 'value']];
        yield 'case insensitive' => [null, '/fOo', ['FOO' => 'value']];
        yield 'remove prefix' => ['/my_app', '/my_app/foo', ['FOO' => 'value']];
        yield 'remove trailing' => ['/my_app/', '/my_app/foo', ['FOO' => 'value']];
        yield 'recursive' => [null, '/foo/bar', ['FOO_BAR' => 'value']];
    }

    public function testMaxResults()
    {
        $maxResults = 50;
        $client = $this->createMock(SsmClient::class);
        $ssmVault = new SsmVault($client, '/', true, $maxResults);

        $client->expects(self::once())
            ->method('getParametersByPath')
            ->with([
                'Path' => '/',
                'Recursive' => true,
                'WithDecryption' => true,
                'MaxResults' => $maxResults,
            ])
            ->willReturn(ResultMockFactory::create(GetParametersByPathResult::class, ['Parameters' => [new Parameter(['Name' => 'foo', 'Value' => 'value'])]]));

        $ssmVault->loadEnvVars();
    }
}
