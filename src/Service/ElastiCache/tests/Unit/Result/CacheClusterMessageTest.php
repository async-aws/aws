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
        // see example-1.json from SDK
        $response = new SimpleMockedResponse(
'<DescribeCacheClustersResult xmlns="http://elasticache.amazonaws.com/doc/2015-02-02/">
  <DescribeCacheClustersResult>
    <CacheClusters>
      <CacheCluster>
        <CacheParameterGroup>
          <ParameterApplyStatus>in-sync</ParameterApplyStatus>
          <CacheParameterGroupName>default.memcached1.4</CacheParameterGroupName>
          <CacheNodeIdsToReboot/>
        </CacheParameterGroup>
        <CacheClusterId>simcoprod42</CacheClusterId>
        <CacheClusterStatus>available</CacheClusterStatus>
        <ConfigurationEndpoint>
          <Port>11211</Port>
          <Address>simcoprod42.m2st2p.cfg.cache.amazonaws.com</Address>
        </ConfigurationEndpoint>
        <ClientDownloadLandingPage>
          https://console.aws.amazon.com/elasticache/home#client-download:
        </ClientDownloadLandingPage>
        <CacheNodeType>cache.m1.large</CacheNodeType>
        <Engine>memcached</Engine>
        <PendingModifiedValues/>
        <PreferredAvailabilityZone>us-west-2c</PreferredAvailabilityZone>
        <CacheClusterCreateTime>2015-02-02T01:21:46.607Z</CacheClusterCreateTime>
        <EngineVersion>1.4.5</EngineVersion>
        <AutoMinorVersionUpgrade>true</AutoMinorVersionUpgrade>
        <PreferredMaintenanceWindow>fri:08:30-fri:09:30</PreferredMaintenanceWindow>
        <CacheSecurityGroups>
          <CacheSecurityGroup>
            <CacheSecurityGroupName>default</CacheSecurityGroupName>
            <Status>active</Status>
          </CacheSecurityGroup>
        </CacheSecurityGroups>
        <NotificationConfiguration>
          <TopicStatus>active</TopicStatus>
          <TopicArn>arn:aws:sns:us-west-2:123456789012:ElastiCacheNotifications</TopicArn>
        </NotificationConfiguration>
        <NumCacheNodes>6</NumCacheNodes>
      </CacheCluster>
    </CacheClusters>
  </DescribeCacheClustersResult>
  <ResponseMetadata>
    <RequestId>f270d58f-b7fb-11e0-9326-b7275b9d4a6c</RequestId>
  </ResponseMetadata>
</DescribeCacheClustersResult>');

        $client = new MockHttpClient($response);
        $result = new CacheClusterMessage(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new ElastiCacheClient(), new DescribeCacheClustersMessage([]));
        $cluster = null;
        foreach ($result->getCacheClusters() as $item) {
            $cluster = $item;

            break;
        }

        self::assertNotNull($cluster, 'There should be at least one cluster in response');
        self::assertSame('memcached', $cluster->getEngine());
    }
}
