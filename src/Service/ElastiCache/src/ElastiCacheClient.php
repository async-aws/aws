<?php

namespace AsyncAws\ElastiCache;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\XmlAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\ElastiCache\Exception\CacheClusterNotFoundFaultException;
use AsyncAws\ElastiCache\Exception\InvalidParameterCombinationException;
use AsyncAws\ElastiCache\Exception\InvalidParameterValueException;
use AsyncAws\ElastiCache\Input\DescribeCacheClustersMessage;
use AsyncAws\ElastiCache\Result\CacheClusterMessage;

class ElastiCacheClient extends AbstractApi
{
    /**
     * Returns information about all provisioned clusters if no cluster identifier is specified, or about a specific cache
     * cluster if a cluster identifier is supplied.
     *
     * By default, abbreviated information about the clusters is returned. You can use the optional *ShowCacheNodeInfo* flag
     * to retrieve detailed information about the cache nodes associated with the clusters. These details include the DNS
     * address and port for the cache node endpoint.
     *
     * If the cluster is in the *creating* state, only cluster-level information is displayed until all of the nodes are
     * successfully provisioned.
     *
     * If the cluster is in the *deleting* state, only cluster-level information is displayed.
     *
     * If cache nodes are currently being added to the cluster, node endpoint information and creation time for the
     * additional nodes are not displayed until they are completely provisioned. When the cluster state is *available*, the
     * cluster is ready for use.
     *
     * If cache nodes are currently being removed from the cluster, no endpoint information for the removed nodes is
     * displayed.
     *
     * @see https://docs.aws.amazon.com/AmazonElastiCache/latest/APIReference/API_DescribeCacheClusters.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-elasticache-2015-02-02.html#describecacheclusters
     *
     * @param array{
     *   CacheClusterId?: string|null,
     *   MaxRecords?: int|null,
     *   Marker?: string|null,
     *   ShowCacheNodeInfo?: bool|null,
     *   ShowCacheClustersNotInReplicationGroups?: bool|null,
     *   '@region'?: string|null,
     * }|DescribeCacheClustersMessage $input
     *
     * @throws CacheClusterNotFoundFaultException
     * @throws InvalidParameterCombinationException
     * @throws InvalidParameterValueException
     */
    public function describeCacheClusters($input = []): CacheClusterMessage
    {
        $input = DescribeCacheClustersMessage::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeCacheClusters', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'CacheClusterNotFound' => CacheClusterNotFoundFaultException::class,
            'InvalidParameterCombination' => InvalidParameterCombinationException::class,
            'InvalidParameterValue' => InvalidParameterValueException::class,
        ]]));

        return new CacheClusterMessage($response, $this, $input);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new XmlAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://elasticache.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'elasticache',
                    'signVersions' => ['v4'],
                ];
            case 'fips':
            case 'us-gov-west-1-fips':
                return [
                    'endpoint' => 'https://elasticache.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'elasticache',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-1-fips':
                return [
                    'endpoint' => 'https://elasticache-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'elasticache',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-2-fips':
                return [
                    'endpoint' => 'https://elasticache-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'elasticache',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-1-fips':
                return [
                    'endpoint' => 'https://elasticache-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'elasticache',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-2-fips':
                return [
                    'endpoint' => 'https://elasticache-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'elasticache',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://elasticache.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'elasticache',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
            case 'us-isob-west-1':
                return [
                    'endpoint' => "https://elasticache.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'elasticache',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://elasticache.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'elasticache',
                    'signVersions' => ['v4'],
                ];
            case 'eu-isoe-west-1':
                return [
                    'endpoint' => 'https://elasticache.eu-isoe-west-1.cloud.adc-e.uk',
                    'signRegion' => 'eu-isoe-west-1',
                    'signService' => 'elasticache',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://elasticache.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'elasticache',
            'signVersions' => ['v4'],
        ];
    }
}
