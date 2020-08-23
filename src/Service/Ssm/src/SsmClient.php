<?php

namespace AsyncAws\Ssm;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;
use AsyncAws\Ssm\Enum\ParameterTier;
use AsyncAws\Ssm\Enum\ParameterType;
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
use AsyncAws\Ssm\ValueObject\Parameter;
use AsyncAws\Ssm\ValueObject\ParameterStringFilter;
use AsyncAws\Ssm\ValueObject\Tag;

class SsmClient extends AbstractApi
{
    /**
     * Delete a parameter from the system.
     *
     * @see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_DeleteParameter.html
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
     * @see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_GetParameter.html
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
     * @see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_GetParameters.html
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
     * @see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_GetParametersByPath.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ssm-2014-11-06.html#getparametersbypath
     *
     * @param array{
     *   Path: string,
     *   Recursive?: bool,
     *   ParameterFilters?: ParameterStringFilter[],
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
     * @see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_PutParameter.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ssm-2014-11-06.html#putparameter
     *
     * @param array{
     *   Name: string,
     *   Description?: string,
     *   Value: string,
     *   Type?: ParameterType::*,
     *   KeyId?: string,
     *   Overwrite?: bool,
     *   AllowedPattern?: string,
     *   Tags?: Tag[],
     *   Tier?: ParameterTier::*,
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
                    'endpoint' => "https://ssm.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://ssm.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1':
            case 'us-gov-west-1':
                return [
                    'endpoint' => "https://ssm.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => "https://ssm.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://ssm-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://ssm-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://ssm.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://ssm.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://ssm-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://ssm-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'ssm-facade-fips-us-east-1':
                return [
                    'endpoint' => 'https://ssm-facade-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'ssm-facade-fips-us-east-2':
                return [
                    'endpoint' => 'https://ssm-facade-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'ssm-facade-fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://ssm-facade.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'ssm-facade-fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://ssm-facade.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'ssm-facade-fips-us-west-1':
                return [
                    'endpoint' => 'https://ssm-facade-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'ssm-facade-fips-us-west-2':
                return [
                    'endpoint' => 'https://ssm-facade-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(sprintf('The region "%s" is not supported by "Ssm".', $region));
    }
}
