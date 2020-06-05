<?php

namespace AsyncAws\CloudFormation;

use AsyncAws\CloudFormation\Input\DescribeStackEventsInput;
use AsyncAws\CloudFormation\Input\DescribeStacksInput;
use AsyncAws\CloudFormation\Result\DescribeStackEventsOutput;
use AsyncAws\CloudFormation\Result\DescribeStacksOutput;
use AsyncAws\CloudFormation\ValueObject\Stack;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;

class CloudFormationClient extends AbstractApi
{
    /**
     * Returns all stack related events for a specified stack in reverse chronological order. For more information about a
     * stack's event history, go to Stacks in the AWS CloudFormation User Guide.
     *
     * @see https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/concept-stack.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cloudformation-2010-05-15.html#describestackevents
     *
     * @param array{
     *   StackName?: string,
     *   NextToken?: string,
     *   @region?: string,
     * }|DescribeStackEventsInput $input
     */
    public function describeStackEvents($input = []): DescribeStackEventsOutput
    {
        $input = DescribeStackEventsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeStackEvents', 'region' => $input->getRegion()]));

        return new DescribeStackEventsOutput($response, $this, $input);
    }

    /**
     * Returns the description for the specified stack; if no stack name was specified, then it returns the description for
     * all the stacks created.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cloudformation-2010-05-15.html#describestacks
     *
     * @param array{
     *   StackName?: string,
     *   NextToken?: string,
     *   @region?: string,
     * }|DescribeStacksInput $input
     */
    public function describeStacks($input = []): DescribeStacksOutput
    {
        $input = DescribeStacksInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeStacks', 'region' => $input->getRegion()]));

        return new DescribeStacksOutput($response, $this, $input);
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
                    'endpoint' => "https://cloudformation.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'cloudformation',
                    'signVersions' => ['v4'],
                ];
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://cloudformation.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'cloudformation',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => "https://cloudformation.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'cloudformation',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => "https://cloudformation.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'cloudformation',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-1-fips':
                return [
                    'endpoint' => 'https://cloudformation-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'cloudformation',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-2-fips':
                return [
                    'endpoint' => 'https://cloudformation-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'cloudformation',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1':
                return [
                    'endpoint' => 'https://cloudformation.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'cloudformation',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1':
                return [
                    'endpoint' => 'https://cloudformation.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'cloudformation',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-1-fips':
                return [
                    'endpoint' => 'https://cloudformation-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'cloudformation',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-2-fips':
                return [
                    'endpoint' => 'https://cloudformation-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'cloudformation',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(sprintf('The region "%s" is not supported by "CloudFormation".', $region));
    }
}
