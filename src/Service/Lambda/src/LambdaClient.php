<?php

namespace AsyncAws\Lambda;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;
use AsyncAws\Lambda\Enum\FunctionVersion;
use AsyncAws\Lambda\Enum\InvocationType;
use AsyncAws\Lambda\Enum\LogType;
use AsyncAws\Lambda\Enum\Runtime;
use AsyncAws\Lambda\Exception\CodeStorageExceededException;
use AsyncAws\Lambda\Exception\EC2AccessDeniedException;
use AsyncAws\Lambda\Exception\EC2ThrottledException;
use AsyncAws\Lambda\Exception\EC2UnexpectedException;
use AsyncAws\Lambda\Exception\EFSIOException;
use AsyncAws\Lambda\Exception\EFSMountConnectivityException;
use AsyncAws\Lambda\Exception\EFSMountFailureException;
use AsyncAws\Lambda\Exception\EFSMountTimeoutException;
use AsyncAws\Lambda\Exception\ENILimitReachedException;
use AsyncAws\Lambda\Exception\InvalidParameterValueException;
use AsyncAws\Lambda\Exception\InvalidRequestContentException;
use AsyncAws\Lambda\Exception\InvalidRuntimeException;
use AsyncAws\Lambda\Exception\InvalidSecurityGroupIDException;
use AsyncAws\Lambda\Exception\InvalidSubnetIDException;
use AsyncAws\Lambda\Exception\InvalidZipFileException;
use AsyncAws\Lambda\Exception\KMSAccessDeniedException;
use AsyncAws\Lambda\Exception\KMSDisabledException;
use AsyncAws\Lambda\Exception\KMSInvalidStateException;
use AsyncAws\Lambda\Exception\KMSNotFoundException;
use AsyncAws\Lambda\Exception\PolicyLengthExceededException;
use AsyncAws\Lambda\Exception\PreconditionFailedException;
use AsyncAws\Lambda\Exception\RequestTooLargeException;
use AsyncAws\Lambda\Exception\ResourceConflictException;
use AsyncAws\Lambda\Exception\ResourceNotFoundException;
use AsyncAws\Lambda\Exception\ResourceNotReadyException;
use AsyncAws\Lambda\Exception\ServiceException;
use AsyncAws\Lambda\Exception\SubnetIPAddressLimitReachedException;
use AsyncAws\Lambda\Exception\TooManyRequestsException;
use AsyncAws\Lambda\Exception\UnsupportedMediaTypeException;
use AsyncAws\Lambda\Input\AddLayerVersionPermissionRequest;
use AsyncAws\Lambda\Input\DeleteFunctionRequest;
use AsyncAws\Lambda\Input\InvocationRequest;
use AsyncAws\Lambda\Input\ListFunctionsRequest;
use AsyncAws\Lambda\Input\ListLayerVersionsRequest;
use AsyncAws\Lambda\Input\ListVersionsByFunctionRequest;
use AsyncAws\Lambda\Input\PublishLayerVersionRequest;
use AsyncAws\Lambda\Result\AddLayerVersionPermissionResponse;
use AsyncAws\Lambda\Result\InvocationResponse;
use AsyncAws\Lambda\Result\ListFunctionsResponse;
use AsyncAws\Lambda\Result\ListLayerVersionsResponse;
use AsyncAws\Lambda\Result\ListVersionsByFunctionResponse;
use AsyncAws\Lambda\Result\PublishLayerVersionResponse;
use AsyncAws\Lambda\ValueObject\LayerVersionContentInput;

class LambdaClient extends AbstractApi
{
    /**
     * Adds permissions to the resource-based policy of a version of an AWS Lambda layer. Use this action to grant layer
     * usage permission to other accounts. You can grant permission to a single account, all AWS accounts, or all accounts
     * in an organization.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/dg/configuration-layers.html
     * @see https://docs.aws.amazon.com/lambda/latest/APIReference/API_AddLayerVersionPermission.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#addlayerversionpermission
     *
     * @param array{
     *   LayerName: string,
     *   VersionNumber: string,
     *   StatementId: string,
     *   Action: string,
     *   Principal: string,
     *   OrganizationId?: string,
     *   RevisionId?: string,
     *   @region?: string,
     * }|AddLayerVersionPermissionRequest $input
     *
     * @throws ServiceException
     * @throws ResourceNotFoundException
     * @throws ResourceConflictException
     * @throws TooManyRequestsException
     * @throws InvalidParameterValueException
     * @throws PolicyLengthExceededException
     * @throws PreconditionFailedException
     */
    public function addLayerVersionPermission($input): AddLayerVersionPermissionResponse
    {
        $input = AddLayerVersionPermissionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AddLayerVersionPermission', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ServiceException' => ServiceException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ResourceConflictException' => ResourceConflictException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'InvalidParameterValueException' => InvalidParameterValueException::class,
            'PolicyLengthExceededException' => PolicyLengthExceededException::class,
            'PreconditionFailedException' => PreconditionFailedException::class,
        ]]));

        return new AddLayerVersionPermissionResponse($response);
    }

    /**
     * Deletes a Lambda function. To delete a specific function version, use the `Qualifier` parameter. Otherwise, all
     * versions and aliases are deleted.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/APIReference/API_DeleteFunction.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#deletefunction
     *
     * @param array{
     *   FunctionName: string,
     *   Qualifier?: string,
     *   @region?: string,
     * }|DeleteFunctionRequest $input
     *
     * @throws ServiceException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws InvalidParameterValueException
     * @throws ResourceConflictException
     */
    public function deleteFunction($input): Result
    {
        $input = DeleteFunctionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteFunction', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ServiceException' => ServiceException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'InvalidParameterValueException' => InvalidParameterValueException::class,
            'ResourceConflictException' => ResourceConflictException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Invokes a Lambda function. You can invoke a function synchronously (and wait for the response), or asynchronously. To
     * invoke a function asynchronously, set `InvocationType` to `Event`.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/APIReference/API_Invoke.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#invoke
     *
     * @param array{
     *   FunctionName: string,
     *   InvocationType?: InvocationType::*,
     *   LogType?: LogType::*,
     *   ClientContext?: string,
     *   Payload?: string,
     *   Qualifier?: string,
     *   @region?: string,
     * }|InvocationRequest $input
     *
     * @throws ServiceException
     * @throws ResourceNotFoundException
     * @throws InvalidRequestContentException
     * @throws RequestTooLargeException
     * @throws UnsupportedMediaTypeException
     * @throws TooManyRequestsException
     * @throws InvalidParameterValueException
     * @throws EC2UnexpectedException
     * @throws SubnetIPAddressLimitReachedException
     * @throws ENILimitReachedException
     * @throws EFSMountConnectivityException
     * @throws EFSMountFailureException
     * @throws EFSMountTimeoutException
     * @throws EFSIOException
     * @throws EC2ThrottledException
     * @throws EC2AccessDeniedException
     * @throws InvalidSubnetIDException
     * @throws InvalidSecurityGroupIDException
     * @throws InvalidZipFileException
     * @throws KMSDisabledException
     * @throws KMSInvalidStateException
     * @throws KMSAccessDeniedException
     * @throws KMSNotFoundException
     * @throws InvalidRuntimeException
     * @throws ResourceConflictException
     * @throws ResourceNotReadyException
     */
    public function invoke($input): InvocationResponse
    {
        $input = InvocationRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Invoke', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ServiceException' => ServiceException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidRequestContentException' => InvalidRequestContentException::class,
            'RequestTooLargeException' => RequestTooLargeException::class,
            'UnsupportedMediaTypeException' => UnsupportedMediaTypeException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'InvalidParameterValueException' => InvalidParameterValueException::class,
            'EC2UnexpectedException' => EC2UnexpectedException::class,
            'SubnetIPAddressLimitReachedException' => SubnetIPAddressLimitReachedException::class,
            'ENILimitReachedException' => ENILimitReachedException::class,
            'EFSMountConnectivityException' => EFSMountConnectivityException::class,
            'EFSMountFailureException' => EFSMountFailureException::class,
            'EFSMountTimeoutException' => EFSMountTimeoutException::class,
            'EFSIOException' => EFSIOException::class,
            'EC2ThrottledException' => EC2ThrottledException::class,
            'EC2AccessDeniedException' => EC2AccessDeniedException::class,
            'InvalidSubnetIDException' => InvalidSubnetIDException::class,
            'InvalidSecurityGroupIDException' => InvalidSecurityGroupIDException::class,
            'InvalidZipFileException' => InvalidZipFileException::class,
            'KMSDisabledException' => KMSDisabledException::class,
            'KMSInvalidStateException' => KMSInvalidStateException::class,
            'KMSAccessDeniedException' => KMSAccessDeniedException::class,
            'KMSNotFoundException' => KMSNotFoundException::class,
            'InvalidRuntimeException' => InvalidRuntimeException::class,
            'ResourceConflictException' => ResourceConflictException::class,
            'ResourceNotReadyException' => ResourceNotReadyException::class,
        ]]));

        return new InvocationResponse($response);
    }

    /**
     * Returns a list of Lambda functions, with the version-specific configuration of each. Lambda returns up to 50
     * functions per call.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/APIReference/API_ListFunctions.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#listfunctions
     *
     * @param array{
     *   MasterRegion?: string,
     *   FunctionVersion?: FunctionVersion::*,
     *   Marker?: string,
     *   MaxItems?: int,
     *   @region?: string,
     * }|ListFunctionsRequest $input
     *
     * @throws ServiceException
     * @throws TooManyRequestsException
     * @throws InvalidParameterValueException
     */
    public function listFunctions($input = []): ListFunctionsResponse
    {
        $input = ListFunctionsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListFunctions', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ServiceException' => ServiceException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'InvalidParameterValueException' => InvalidParameterValueException::class,
        ]]));

        return new ListFunctionsResponse($response, $this, $input);
    }

    /**
     * Lists the versions of an AWS Lambda layer. Versions that have been deleted aren't listed. Specify a runtime
     * identifier to list only versions that indicate that they're compatible with that runtime.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/dg/configuration-layers.html
     * @see https://docs.aws.amazon.com/lambda/latest/dg/lambda-runtimes.html
     * @see https://docs.aws.amazon.com/lambda/latest/APIReference/API_ListLayerVersions.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#listlayerversions
     *
     * @param array{
     *   CompatibleRuntime?: Runtime::*,
     *   LayerName: string,
     *   Marker?: string,
     *   MaxItems?: int,
     *   @region?: string,
     * }|ListLayerVersionsRequest $input
     *
     * @throws ServiceException
     * @throws InvalidParameterValueException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     */
    public function listLayerVersions($input): ListLayerVersionsResponse
    {
        $input = ListLayerVersionsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListLayerVersions', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ServiceException' => ServiceException::class,
            'InvalidParameterValueException' => InvalidParameterValueException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new ListLayerVersionsResponse($response, $this, $input);
    }

    /**
     * Returns a list of versions, with the version-specific configuration of each. Lambda returns up to 50 versions per
     * call.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/dg/versioning-aliases.html
     * @see https://docs.aws.amazon.com/lambda/latest/APIReference/API_ListVersionsByFunction.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#listversionsbyfunction
     *
     * @param array{
     *   FunctionName: string,
     *   Marker?: string,
     *   MaxItems?: int,
     *   @region?: string,
     * }|ListVersionsByFunctionRequest $input
     *
     * @throws ServiceException
     * @throws ResourceNotFoundException
     * @throws InvalidParameterValueException
     * @throws TooManyRequestsException
     */
    public function listVersionsByFunction($input): ListVersionsByFunctionResponse
    {
        $input = ListVersionsByFunctionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListVersionsByFunction', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ServiceException' => ServiceException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterValueException' => InvalidParameterValueException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new ListVersionsByFunctionResponse($response, $this, $input);
    }

    /**
     * Creates an AWS Lambda layer from a ZIP archive. Each time you call `PublishLayerVersion` with the same layer name, a
     * new version is created.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/dg/configuration-layers.html
     * @see https://docs.aws.amazon.com/lambda/latest/APIReference/API_PublishLayerVersion.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#publishlayerversion
     *
     * @param array{
     *   LayerName: string,
     *   Description?: string,
     *   Content: LayerVersionContentInput|array,
     *   CompatibleRuntimes?: list<Runtime::*>,
     *   LicenseInfo?: string,
     *   @region?: string,
     * }|PublishLayerVersionRequest $input
     *
     * @throws ServiceException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws InvalidParameterValueException
     * @throws CodeStorageExceededException
     */
    public function publishLayerVersion($input): PublishLayerVersionResponse
    {
        $input = PublishLayerVersionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PublishLayerVersion', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ServiceException' => ServiceException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'InvalidParameterValueException' => InvalidParameterValueException::class,
            'CodeStorageExceededException' => CodeStorageExceededException::class,
        ]]));

        return new PublishLayerVersionResponse($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRestAwsErrorFactory();
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
                    'endpoint' => "https://lambda.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'lambda',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1':
            case 'us-gov-west-1':
                return [
                    'endpoint' => "https://lambda.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'lambda',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => "https://lambda.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'lambda',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => "https://lambda.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'lambda',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://lambda-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'lambda',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://lambda-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'lambda',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://lambda-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'lambda',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://lambda-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'lambda',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://lambda-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'lambda',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://lambda-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'lambda',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://lambda.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'lambda',
            'signVersions' => ['v4'],
        ];
    }
}
