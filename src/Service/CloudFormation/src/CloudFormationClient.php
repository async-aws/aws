<?php

namespace AsyncAws\CloudFormation;

use AsyncAws\CloudFormation\Input\DescribeStackDriftDetectionStatusInput;
use AsyncAws\CloudFormation\Input\DescribeStackEventsInput;
use AsyncAws\CloudFormation\Input\DescribeStacksInput;
use AsyncAws\CloudFormation\Result\DescribeStackDriftDetectionStatusOutput;
use AsyncAws\CloudFormation\Result\DescribeStackEventsOutput;
use AsyncAws\CloudFormation\Result\DescribeStacksOutput;
use AsyncAws\CloudFormation\ValueObject\Stack;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\XmlAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;

class CloudFormationClient extends AbstractApi
{
    /**
     * Returns information about a stack drift detection operation. A stack drift detection operation detects whether a
     * stack's actual configuration differs, or has *drifted*, from its expected configuration, as defined in the stack
     * template and any values specified as template parameters. A stack is considered to have drifted if one or more of its
     * resources have drifted. For more information about stack and resource drift, see Detect unmanaged configuration
     * changes to stacks and resources with drift detection [^1].
     *
     * Use DetectStackDrift to initiate a stack drift detection operation. `DetectStackDrift` returns a
     * `StackDriftDetectionId` you can use to monitor the progress of the operation using
     * `DescribeStackDriftDetectionStatus`. Once the drift detection operation has completed, use
     * DescribeStackResourceDrifts to return drift information about the stack and its resources.
     *
     * [^1]: https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/using-cfn-stack-drift.html
     *
     * @see https://docs.aws.amazon.com/AWSCloudFormation/latest/APIReference/API_DescribeStackDriftDetectionStatus.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cloudformation-2010-05-15.html#describestackdriftdetectionstatus
     *
     * @param array{
     *   StackDriftDetectionId: string,
     *   '@region'?: string|null,
     * }|DescribeStackDriftDetectionStatusInput $input
     */
    public function describeStackDriftDetectionStatus($input): DescribeStackDriftDetectionStatusOutput
    {
        $input = DescribeStackDriftDetectionStatusInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeStackDriftDetectionStatus', 'region' => $input->getRegion()]));

        return new DescribeStackDriftDetectionStatusOutput($response);
    }

    /**
     * Returns all stack related events for a specified stack in reverse chronological order. For more information about a
     * stack's event history, see Understand CloudFormation stack creation events [^1] in the *CloudFormation User Guide*.
     *
     * > You can list events for stacks that have failed to create or have been deleted by specifying the unique stack
     * > identifier (stack ID).
     *
     * [^1]: https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/stack-resource-configuration-complete.html
     *
     * @see https://docs.aws.amazon.com/AWSCloudFormation/latest/APIReference/API_DescribeStackEvents.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cloudformation-2010-05-15.html#describestackevents
     *
     * @param array{
     *   StackName?: string|null,
     *   NextToken?: string|null,
     *   '@region'?: string|null,
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
     * all the stacks created. For more information about a stack's event history, see Understand CloudFormation stack
     * creation events [^1] in the *CloudFormation User Guide*.
     *
     * > If the stack doesn't exist, a `ValidationError` is returned.
     *
     * [^1]: https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/stack-resource-configuration-complete.html
     *
     * @see https://docs.aws.amazon.com/AWSCloudFormation/latest/APIReference/API_DescribeStacks.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cloudformation-2010-05-15.html#describestacks
     *
     * @param array{
     *   StackName?: string|null,
     *   NextToken?: string|null,
     *   '@region'?: string|null,
     * }|DescribeStacksInput $input
     */
    public function describeStacks($input = []): DescribeStacksOutput
    {
        $input = DescribeStacksInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeStacks', 'region' => $input->getRegion()]));

        return new DescribeStacksOutput($response, $this, $input);
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
                    'endpoint' => "https://cloudformation.$region.amazonaws.com.cn",
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
            case 'us-gov-east-1-fips':
                return [
                    'endpoint' => 'https://cloudformation.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'cloudformation',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1-fips':
                return [
                    'endpoint' => 'https://cloudformation.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'cloudformation',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://cloudformation.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'cloudformation',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
            case 'us-isob-west-1':
                return [
                    'endpoint' => "https://cloudformation.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'cloudformation',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://cloudformation.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'cloudformation',
                    'signVersions' => ['v4'],
                ];
            case 'eu-isoe-west-1':
                return [
                    'endpoint' => 'https://cloudformation.eu-isoe-west-1.cloud.adc-e.uk',
                    'signRegion' => 'eu-isoe-west-1',
                    'signService' => 'cloudformation',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://cloudformation.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'cloudformation',
            'signVersions' => ['v4'],
        ];
    }
}
