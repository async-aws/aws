<?php

namespace AsyncAws\Ssm;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\RequestContext;
use AsyncAws\Ssm\Input\DeleteParameterRequest;
use AsyncAws\Ssm\Input\GetParameterRequest;
use AsyncAws\Ssm\Input\GetParametersByPathRequest;
use AsyncAws\Ssm\Input\GetParametersRequest;
use AsyncAws\Ssm\Input\PutParameterRequest;
use AsyncAws\Ssm\Result\DeleteParameterResult;
use AsyncAws\Ssm\Result\GetParameterResult;
use AsyncAws\Ssm\Result\GetParametersByPathResult;
use AsyncAws\Ssm\Result\GetParametersResult;
use AsyncAws\Ssm\Result\PutParameterResult;

class SsmClient extends AbstractApi
{
    /**
     * Delete a parameter from the system.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ssm-2014-11-06.html#deleteparameter
     *
     * @param array{
     *   Name: string,
     *   @region?: string,
     * }|DeleteParameterRequest $input
     */
    public function deleteParameter($input): DeleteParameterResult
    {
        $input = DeleteParameterRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteParameter', 'region' => $input->getRegion()]));

        return new DeleteParameterResult($response);
    }

    /**
     * Get information about a parameter by using the parameter name. Don't confuse this API action with the GetParameters
     * API action.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ssm-2014-11-06.html#getparameter
     *
     * @param array{
     *   Name: string,
     *   WithDecryption?: bool,
     *   @region?: string,
     * }|GetParameterRequest $input
     */
    public function getParameter($input): GetParameterResult
    {
        $input = GetParameterRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetParameter', 'region' => $input->getRegion()]));

        return new GetParameterResult($response);
    }

    /**
     * Get details of a parameter. Don't confuse this API action with the GetParameter API action.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ssm-2014-11-06.html#getparameters
     *
     * @param array{
     *   Names: string[],
     *   WithDecryption?: bool,
     *   @region?: string,
     * }|GetParametersRequest $input
     */
    public function getParameters($input): GetParametersResult
    {
        $input = GetParametersRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetParameters', 'region' => $input->getRegion()]));

        return new GetParametersResult($response);
    }

    /**
     * Retrieve information about one or more parameters in a specific hierarchy.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ssm-2014-11-06.html#getparametersbypath
     *
     * @param array{
     *   Path: string,
     *   Recursive?: bool,
     *   ParameterFilters?: \AsyncAws\Ssm\ValueObject\ParameterStringFilter[],
     *   WithDecryption?: bool,
     *   MaxResults?: int,
     *   NextToken?: string,
     *   @region?: string,
     * }|GetParametersByPathRequest $input
     */
    public function getParametersByPath($input): GetParametersByPathResult
    {
        $input = GetParametersByPathRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetParametersByPath', 'region' => $input->getRegion()]));

        return new GetParametersByPathResult($response, $this, $input);
    }

    /**
     * Add a parameter to the system.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ssm-2014-11-06.html#putparameter
     *
     * @param array{
     *   Name: string,
     *   Description?: string,
     *   Value: string,
     *   Type?: \AsyncAws\Ssm\Enum\ParameterType::*,
     *   KeyId?: string,
     *   Overwrite?: bool,
     *   AllowedPattern?: string,
     *   Tags?: \AsyncAws\Ssm\ValueObject\Tag[],
     *   Tier?: \AsyncAws\Ssm\Enum\ParameterTier::*,
     *   Policies?: string,
     *   DataType?: string,
     *   @region?: string,
     * }|PutParameterRequest $input
     */
    public function putParameter($input): PutParameterResult
    {
        $input = PutParameterRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutParameter', 'region' => $input->getRegion()]));

        return new PutParameterResult($response);
    }

    protected function getServiceCode(): string
    {
        return 'ssm';
    }

    protected function getSignatureScopeName(): string
    {
        return 'ssm';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
