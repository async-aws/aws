<?php

namespace AsyncAws\Ssm;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Ssm\Enum\ParameterTier;
use AsyncAws\Ssm\Enum\ParameterType;
use AsyncAws\Ssm\Exception\HierarchyLevelLimitExceededException;
use AsyncAws\Ssm\Exception\HierarchyTypeMismatchException;
use AsyncAws\Ssm\Exception\IncompatiblePolicyException;
use AsyncAws\Ssm\Exception\InternalServerErrorException;
use AsyncAws\Ssm\Exception\InvalidAllowedPatternException;
use AsyncAws\Ssm\Exception\InvalidFilterKeyException;
use AsyncAws\Ssm\Exception\InvalidFilterOptionException;
use AsyncAws\Ssm\Exception\InvalidFilterValueException;
use AsyncAws\Ssm\Exception\InvalidKeyIdException;
use AsyncAws\Ssm\Exception\InvalidNextTokenException;
use AsyncAws\Ssm\Exception\InvalidPolicyAttributeException;
use AsyncAws\Ssm\Exception\InvalidPolicyTypeException;
use AsyncAws\Ssm\Exception\ParameterAlreadyExistsException;
use AsyncAws\Ssm\Exception\ParameterLimitExceededException;
use AsyncAws\Ssm\Exception\ParameterMaxVersionLimitExceededException;
use AsyncAws\Ssm\Exception\ParameterNotFoundException;
use AsyncAws\Ssm\Exception\ParameterPatternMismatchException;
use AsyncAws\Ssm\Exception\ParameterVersionNotFoundException;
use AsyncAws\Ssm\Exception\PoliciesLimitExceededException;
use AsyncAws\Ssm\Exception\TooManyUpdatesException;
use AsyncAws\Ssm\Exception\UnsupportedParameterTypeException;
use AsyncAws\Ssm\Input\DeleteParameterRequest;
use AsyncAws\Ssm\Input\DeleteParametersRequest;
use AsyncAws\Ssm\Input\GetParameterRequest;
use AsyncAws\Ssm\Input\GetParametersByPathRequest;
use AsyncAws\Ssm\Input\GetParametersRequest;
use AsyncAws\Ssm\Input\PutParameterRequest;
use AsyncAws\Ssm\Result\DeleteParameterResult;
use AsyncAws\Ssm\Result\DeleteParametersResult;
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
     * Delete a parameter from the system. After deleting a parameter, wait for at least 30 seconds to create a parameter
     * with the same name.
     *
     * @see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_DeleteParameter.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ssm-2014-11-06.html#deleteparameter
     *
     * @param array{
     *   Name: string,
     *   '@region'?: string|null,
     * }|DeleteParameterRequest $input
     *
     * @throws InternalServerErrorException
     * @throws ParameterNotFoundException
     */
    public function deleteParameter($input): DeleteParameterResult
    {
        $input = DeleteParameterRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteParameter', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
            'ParameterNotFound' => ParameterNotFoundException::class,
        ]]));

        return new DeleteParameterResult($response);
    }

    /**
     * Delete a list of parameters. After deleting a parameter, wait for at least 30 seconds to create a parameter with the
     * same name.
     *
     * @see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_DeleteParameters.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ssm-2014-11-06.html#deleteparameters
     *
     * @param array{
     *   Names: string[],
     *   '@region'?: string|null,
     * }|DeleteParametersRequest $input
     *
     * @throws InternalServerErrorException
     */
    public function deleteParameters($input): DeleteParametersResult
    {
        $input = DeleteParametersRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteParameters', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
        ]]));

        return new DeleteParametersResult($response);
    }

    /**
     * Get information about a single parameter by specifying the parameter name.
     *
     * > To get information about more than one parameter at a time, use the GetParameters operation.
     *
     * @see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_GetParameter.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ssm-2014-11-06.html#getparameter
     *
     * @param array{
     *   Name: string,
     *   WithDecryption?: null|bool,
     *   '@region'?: string|null,
     * }|GetParameterRequest $input
     *
     * @throws InternalServerErrorException
     * @throws InvalidKeyIdException
     * @throws ParameterNotFoundException
     * @throws ParameterVersionNotFoundException
     */
    public function getParameter($input): GetParameterResult
    {
        $input = GetParameterRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetParameter', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
            'InvalidKeyId' => InvalidKeyIdException::class,
            'ParameterNotFound' => ParameterNotFoundException::class,
            'ParameterVersionNotFound' => ParameterVersionNotFoundException::class,
        ]]));

        return new GetParameterResult($response);
    }

    /**
     * Get information about one or more parameters by specifying multiple parameter names.
     *
     * > To get information about a single parameter, you can use the GetParameter operation instead.
     *
     * @see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_GetParameters.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ssm-2014-11-06.html#getparameters
     *
     * @param array{
     *   Names: string[],
     *   WithDecryption?: null|bool,
     *   '@region'?: string|null,
     * }|GetParametersRequest $input
     *
     * @throws InternalServerErrorException
     * @throws InvalidKeyIdException
     */
    public function getParameters($input): GetParametersResult
    {
        $input = GetParametersRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetParameters', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
            'InvalidKeyId' => InvalidKeyIdException::class,
        ]]));

        return new GetParametersResult($response);
    }

    /**
     * Retrieve information about one or more parameters under a specified level in a hierarchy.
     *
     * Request results are returned on a best-effort basis. If you specify `MaxResults` in the request, the response
     * includes information up to the limit specified. The number of items returned, however, can be between zero and the
     * value of `MaxResults`. If the service reaches an internal limit while processing the results, it stops the operation
     * and returns the matching values up to that point and a `NextToken`. You can specify the `NextToken` in a subsequent
     * call to get the next set of results.
     *
     * @see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_GetParametersByPath.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ssm-2014-11-06.html#getparametersbypath
     *
     * @param array{
     *   Path: string,
     *   Recursive?: null|bool,
     *   ParameterFilters?: null|array<ParameterStringFilter|array>,
     *   WithDecryption?: null|bool,
     *   MaxResults?: null|int,
     *   NextToken?: null|string,
     *   '@region'?: string|null,
     * }|GetParametersByPathRequest $input
     *
     * @throws InternalServerErrorException
     * @throws InvalidFilterKeyException
     * @throws InvalidFilterOptionException
     * @throws InvalidFilterValueException
     * @throws InvalidKeyIdException
     * @throws InvalidNextTokenException
     */
    public function getParametersByPath($input): GetParametersByPathResult
    {
        $input = GetParametersByPathRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetParametersByPath', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
            'InvalidFilterKey' => InvalidFilterKeyException::class,
            'InvalidFilterOption' => InvalidFilterOptionException::class,
            'InvalidFilterValue' => InvalidFilterValueException::class,
            'InvalidKeyId' => InvalidKeyIdException::class,
            'InvalidNextToken' => InvalidNextTokenException::class,
        ]]));

        return new GetParametersByPathResult($response, $this, $input);
    }

    /**
     * Create or update a parameter in Parameter Store.
     *
     * @see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_PutParameter.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ssm-2014-11-06.html#putparameter
     *
     * @param array{
     *   Name: string,
     *   Description?: null|string,
     *   Value: string,
     *   Type?: null|ParameterType::*,
     *   KeyId?: null|string,
     *   Overwrite?: null|bool,
     *   AllowedPattern?: null|string,
     *   Tags?: null|array<Tag|array>,
     *   Tier?: null|ParameterTier::*,
     *   Policies?: null|string,
     *   DataType?: null|string,
     *   '@region'?: string|null,
     * }|PutParameterRequest $input
     *
     * @throws HierarchyLevelLimitExceededException
     * @throws HierarchyTypeMismatchException
     * @throws IncompatiblePolicyException
     * @throws InternalServerErrorException
     * @throws InvalidAllowedPatternException
     * @throws InvalidKeyIdException
     * @throws InvalidPolicyAttributeException
     * @throws InvalidPolicyTypeException
     * @throws ParameterAlreadyExistsException
     * @throws ParameterLimitExceededException
     * @throws ParameterMaxVersionLimitExceededException
     * @throws ParameterPatternMismatchException
     * @throws PoliciesLimitExceededException
     * @throws TooManyUpdatesException
     * @throws UnsupportedParameterTypeException
     */
    public function putParameter($input): PutParameterResult
    {
        $input = PutParameterRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutParameter', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'HierarchyLevelLimitExceededException' => HierarchyLevelLimitExceededException::class,
            'HierarchyTypeMismatchException' => HierarchyTypeMismatchException::class,
            'IncompatiblePolicyException' => IncompatiblePolicyException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'InvalidAllowedPatternException' => InvalidAllowedPatternException::class,
            'InvalidKeyId' => InvalidKeyIdException::class,
            'InvalidPolicyAttributeException' => InvalidPolicyAttributeException::class,
            'InvalidPolicyTypeException' => InvalidPolicyTypeException::class,
            'ParameterAlreadyExists' => ParameterAlreadyExistsException::class,
            'ParameterLimitExceeded' => ParameterLimitExceededException::class,
            'ParameterMaxVersionLimitExceeded' => ParameterMaxVersionLimitExceededException::class,
            'ParameterPatternMismatchException' => ParameterPatternMismatchException::class,
            'PoliciesLimitExceededException' => PoliciesLimitExceededException::class,
            'TooManyUpdates' => TooManyUpdatesException::class,
            'UnsupportedParameterType' => UnsupportedParameterTypeException::class,
        ]]));

        return new PutParameterResult($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRpcAwsErrorFactory();
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
                    'endpoint' => "https://ssm.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'fips-ca-central-1':
                return [
                    'endpoint' => 'https://ssm-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'fips-ca-west-1':
                return [
                    'endpoint' => 'https://ssm-fips.ca-west-1.amazonaws.com',
                    'signRegion' => 'ca-west-1',
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
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://ssm.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://ssm.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'eu-isoe-west-1':
                return [
                    'endpoint' => 'https://ssm.eu-isoe-west-1.cloud.adc-e.uk',
                    'signRegion' => 'eu-isoe-west-1',
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://ssm.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'ssm',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://ssm.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'ssm',
            'signVersions' => ['v4'],
        ];
    }
}
