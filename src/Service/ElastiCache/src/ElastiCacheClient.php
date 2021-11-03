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
     * @see https://docs.aws.amazon.com/AmazonElastiCache/latest/APIReference/API_DescribeCacheClusters.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-elasticache-2015-02-02.html#describecacheclusters
     *
     * @param array{
     *   CacheClusterId?: string,
     *   MaxRecords?: int,
     *   Marker?: string,
     *   ShowCacheNodeInfo?: bool,
     *   ShowCacheClustersNotInReplicationGroups?: bool,
     *   @region?: string,
     * }|DescribeCacheClustersMessage $input
     *
     * @throws CacheClusterNotFoundFaultException
     * @throws InvalidParameterValueException
     * @throws InvalidParameterCombinationException
     */
    public function describeCacheClusters($input = []): CacheClusterMessage
    {
        $input = DescribeCacheClustersMessage::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeCacheClusters', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'CacheClusterNotFound' => CacheClusterNotFoundFaultException::class,
            'InvalidParameterValue' => InvalidParameterValueException::class,
            'InvalidParameterCombination' => InvalidParameterCombinationException::class,
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
            case 'us-gov-east-1':
                return [
                    'endpoint' => "https://elasticache.$region.amazonaws.com",
                    'signRegion' => $region,
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
                return [
                    'endpoint' => "https://elasticache.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'elasticache',
                    'signVersions' => ['v4'],
                ];
            case 'fips':
                return [
                    'endpoint' => 'https://elasticache.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'elasticache',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-1':
                return [
                    'endpoint' => 'https://elasticache.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
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
            case 'us-east-2':
                return [
                    'endpoint' => 'https://elasticache.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
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
            case 'us-gov-west-1':
                return [
                    'endpoint' => 'https://elasticache.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'elasticache',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1-fips':
                return [
                    'endpoint' => 'https://elasticache.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'elasticache',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-1':
                return [
                    'endpoint' => 'https://elasticache.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
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
            case 'us-west-2':
                return [
                    'endpoint' => 'https://elasticache.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
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
        }

        return [
            'endpoint' => "https://elasticache.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'elasticache',
            'signVersions' => ['v4'],
        ];
    }
}
