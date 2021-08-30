<?php

namespace AsyncAws\ElastiCache\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\ElastiCache\ElastiCacheClient;
use AsyncAws\ElastiCache\Input\DescribeCacheClustersMessage;
use AsyncAws\ElastiCache\Result\CacheClusterMessage;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CacheClusterMessageTest extends TestCase
{
    public function testCacheClusterMessage(): void
    {
        self::fail('Not implemented');

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<CacheClusters>
          <member>
            <AutoMinorVersionUpgrade>1</AutoMinorVersionUpgrade>
            <CacheClusterCreateTime>2016-12-21T21:59:43.794Z</CacheClusterCreateTime>
            <CacheClusterId>my-mem-cluster</CacheClusterId>
            <CacheClusterStatus>available</CacheClusterStatus>
            <CacheNodeType>cache.t2.medium</CacheNodeType>
            <CacheParameterGroup>
              <CacheNodeIdsToReboot/>
              <CacheParameterGroupName>default.memcached1.4</CacheParameterGroupName>
              <ParameterApplyStatus>in-sync</ParameterApplyStatus>
            </CacheParameterGroup>
            <CacheSecurityGroups/>
            <CacheSubnetGroupName>default</CacheSubnetGroupName>
            <ClientDownloadLandingPage>https://console.aws.amazon.com/elasticache/home#client-download:</ClientDownloadLandingPage>
            <ConfigurationEndpoint>
              <Address>my-mem-cluster.abcdef.cfg.use1.cache.amazonaws.com</Address>
              <Port>11211</Port>
            </ConfigurationEndpoint>
            <Engine>memcached</Engine>
            <EngineVersion>1.4.24</EngineVersion>
            <NumCacheNodes>2</NumCacheNodes>
            <PendingModifiedValues/>
            <PreferredAvailabilityZone>Multiple</PreferredAvailabilityZone>
            <PreferredMaintenanceWindow>wed:06:00-wed:07:00</PreferredMaintenanceWindow>
          </member>
        </CacheClusters>');

        $client = new MockHttpClient($response);
        $result = new CacheClusterMessage(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new ElastiCacheClient(), new DescribeCacheClustersMessage([]));

        self::assertSame('changeIt', $result->getMarker());
        // self::assertTODO(expected, $result->getCacheClusters());
    }
}
