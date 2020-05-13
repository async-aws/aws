<?php

namespace AsyncAws\CodeDeploy;

use AsyncAws\CodeDeploy\Input\PutLifecycleEventHookExecutionStatusInput;
use AsyncAws\CodeDeploy\Result\PutLifecycleEventHookExecutionStatusOutput;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;

class CodeDeployClient extends AbstractApi
{
    /**
     * Sets the result of a Lambda validation function. The function validates one or both lifecycle events
     * (`BeforeAllowTraffic` and `AfterAllowTraffic`) and returns `Succeeded` or `Failed`.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codedeploy-2014-10-06.html#putlifecycleeventhookexecutionstatus
     *
     * @param array{
     *   deploymentId?: string,
     *   lifecycleEventHookExecutionId?: string,
     *   status?: \AsyncAws\CodeDeploy\Enum\LifecycleEventStatus::*,
     *   @region?: string,
     * }|PutLifecycleEventHookExecutionStatusInput $input
     */
    public function putLifecycleEventHookExecutionStatus($input = []): PutLifecycleEventHookExecutionStatusOutput
    {
        $input = PutLifecycleEventHookExecutionStatusInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutLifecycleEventHookExecutionStatus', 'region' => $input->getRegion()]));

        return new PutLifecycleEventHookExecutionStatusOutput($response);
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'af-south-1':
            case 'ap-east-1':
            case 'ap-northeast-1':
            case 'ap-northeast-2':
            case 'ap-south-1':
            case 'ap-southeast-1':
            case 'ap-southeast-2':
            case 'ca-central-1':
            case 'eu-central-1':
            case 'eu-north-1':
            case 'eu-south-1':
            case 'eu-west-1':
            case 'eu-west-2':
            case 'eu-west-3':
            case 'me-south-1':
            case 'sa-east-1':
            case 'us-east-1':
            case 'us-east-2':
            case 'us-west-1':
            case 'us-west-2':
                return [
                    'endpoint' => 'https://codedeploy.%region%.amazonaws.com',
                    'signRegion' => $region,
                    'signService' => 'codedeploy',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => 'https://codedeploy.%region%.amazonaws.com.cn',
                    'signRegion' => $region,
                    'signService' => 'codedeploy',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-gov-east-1':
            case 'us-gov-west-1':
                return [
                    'endpoint' => 'https://codedeploy.%region%.amazonaws.com',
                    'signRegion' => $region,
                    'signService' => 'codedeploy',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => 'https://codedeploy.%region%.c2s.ic.gov',
                    'signRegion' => $region,
                    'signService' => 'codedeploy',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-east-1-fips':
                return [
                    'endpoint' => 'https://codedeploy-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'codedeploy',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-east-2-fips':
                return [
                    'endpoint' => 'https://codedeploy-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'codedeploy',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-gov-east-1-fips':
                return [
                    'endpoint' => 'https://codedeploy-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'codedeploy',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-gov-west-1-fips':
                return [
                    'endpoint' => 'https://codedeploy-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'codedeploy',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-west-1-fips':
                return [
                    'endpoint' => 'https://codedeploy-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'codedeploy',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-west-2-fips':
                return [
                    'endpoint' => 'https://codedeploy-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'codedeploy',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
        }

        throw new UnsupportedRegion(sprintf('The region "%s" is not supported by "CodeDeploy".', $region));
    }

    protected function getServiceCode(): string
    {
        return 'codedeploy';
    }

    protected function getSignatureScopeName(): string
    {
        return 'codedeploy';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
