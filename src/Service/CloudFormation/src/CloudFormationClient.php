<?php

namespace AsyncAws\CloudFormation;

use AsyncAws\CloudFormation\Input\DescribeStackEventsInput;
use AsyncAws\CloudFormation\Input\DescribeStacksInput;
use AsyncAws\CloudFormation\Result\DescribeStackEventsOutput;
use AsyncAws\CloudFormation\Result\DescribeStacksOutput;
use AsyncAws\Core\AbstractApi;
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

    protected function getServiceCode(): string
    {
        return 'cloudformation';
    }

    protected function getSignatureScopeName(): string
    {
        return 'cloudformation';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
