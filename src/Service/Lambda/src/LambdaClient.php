<?php

namespace AsyncAws\Lambda;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;
use AsyncAws\Lambda\Enum\Architecture;
use AsyncAws\Lambda\Enum\FunctionVersion;
use AsyncAws\Lambda\Enum\InvocationType;
use AsyncAws\Lambda\Enum\LogType;
use AsyncAws\Lambda\Enum\Runtime;
use AsyncAws\Lambda\Exception\CodeSigningConfigNotFoundException;
use AsyncAws\Lambda\Exception\CodeStorageExceededException;
use AsyncAws\Lambda\Exception\CodeVerificationFailedException;
use AsyncAws\Lambda\Exception\EC2AccessDeniedException;
use AsyncAws\Lambda\Exception\EC2ThrottledException;
use AsyncAws\Lambda\Exception\EC2UnexpectedException;
use AsyncAws\Lambda\Exception\EFSIOException;
use AsyncAws\Lambda\Exception\EFSMountConnectivityException;
use AsyncAws\Lambda\Exception\EFSMountFailureException;
use AsyncAws\Lambda\Exception\EFSMountTimeoutException;
use AsyncAws\Lambda\Exception\ENILimitReachedException;
use AsyncAws\Lambda\Exception\InvalidCodeSignatureException;
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
use AsyncAws\Lambda\Exception\RecursiveInvocationException;
use AsyncAws\Lambda\Exception\RequestTooLargeException;
use AsyncAws\Lambda\Exception\ResourceConflictException;
use AsyncAws\Lambda\Exception\ResourceNotFoundException;
use AsyncAws\Lambda\Exception\ResourceNotReadyException;
use AsyncAws\Lambda\Exception\SerializedRequestEntityTooLargeException;
use AsyncAws\Lambda\Exception\ServiceException;
use AsyncAws\Lambda\Exception\SnapStartException;
use AsyncAws\Lambda\Exception\SnapStartNotReadyException;
use AsyncAws\Lambda\Exception\SnapStartTimeoutException;
use AsyncAws\Lambda\Exception\SubnetIPAddressLimitReachedException;
use AsyncAws\Lambda\Exception\TooManyRequestsException;
use AsyncAws\Lambda\Exception\UnsupportedMediaTypeException;
use AsyncAws\Lambda\Input\AddLayerVersionPermissionRequest;
use AsyncAws\Lambda\Input\DeleteFunctionRequest;
use AsyncAws\Lambda\Input\GetFunctionConfigurationRequest;
use AsyncAws\Lambda\Input\InvocationRequest;
use AsyncAws\Lambda\Input\ListFunctionsRequest;
use AsyncAws\Lambda\Input\ListLayerVersionsRequest;
use AsyncAws\Lambda\Input\ListVersionsByFunctionRequest;
use AsyncAws\Lambda\Input\PublishLayerVersionRequest;
use AsyncAws\Lambda\Input\UpdateFunctionConfigurationRequest;
use AsyncAws\Lambda\Result\AddLayerVersionPermissionResponse;
use AsyncAws\Lambda\Result\FunctionConfiguration;
use AsyncAws\Lambda\Result\InvocationResponse;
use AsyncAws\Lambda\Result\ListFunctionsResponse;
use AsyncAws\Lambda\Result\ListLayerVersionsResponse;
use AsyncAws\Lambda\Result\ListVersionsByFunctionResponse;
use AsyncAws\Lambda\Result\PublishLayerVersionResponse;
use AsyncAws\Lambda\ValueObject\DeadLetterConfig;
use AsyncAws\Lambda\ValueObject\Environment;
use AsyncAws\Lambda\ValueObject\EphemeralStorage;
use AsyncAws\Lambda\ValueObject\FileSystemConfig;
use AsyncAws\Lambda\ValueObject\ImageConfig;
use AsyncAws\Lambda\ValueObject\LayerVersionContentInput;
use AsyncAws\Lambda\ValueObject\LoggingConfig;
use AsyncAws\Lambda\ValueObject\SnapStart;
use AsyncAws\Lambda\ValueObject\TracingConfig;
use AsyncAws\Lambda\ValueObject\VpcConfig;

class LambdaClient extends AbstractApi
{
    /**
     * Adds permissions to the resource-based policy of a version of an Lambda layer [^1]. Use this action to grant layer
     * usage permission to other accounts. You can grant permission to a single account, all accounts in an organization, or
     * all Amazon Web Services accounts.
     *
     * To revoke permission, call RemoveLayerVersionPermission with the statement ID that you specified when you added it.
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-layers.html
     *
     * @see https://docs.aws.amazon.com/lambda/latest/APIReference/API_AddLayerVersionPermission.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#addlayerversionpermission
     *
     * @param array{
     *   LayerName: string,
     *   VersionNumber: int,
     *   StatementId: string,
     *   Action: string,
     *   Principal: string,
     *   OrganizationId?: string|null,
     *   RevisionId?: string|null,
     *   '@region'?: string|null,
     * }|AddLayerVersionPermissionRequest $input
     *
     * @throws InvalidParameterValueException
     * @throws PolicyLengthExceededException
     * @throws PreconditionFailedException
     * @throws ResourceConflictException
     * @throws ResourceNotFoundException
     * @throws ServiceException
     * @throws TooManyRequestsException
     */
    public function addLayerVersionPermission($input): AddLayerVersionPermissionResponse
    {
        $input = AddLayerVersionPermissionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AddLayerVersionPermission', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterValueException' => InvalidParameterValueException::class,
            'PolicyLengthExceededException' => PolicyLengthExceededException::class,
            'PreconditionFailedException' => PreconditionFailedException::class,
            'ResourceConflictException' => ResourceConflictException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceException' => ServiceException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new AddLayerVersionPermissionResponse($response);
    }

    /**
     * Deletes a Lambda function. To delete a specific function version, use the `Qualifier` parameter. Otherwise, all
     * versions and aliases are deleted. This doesn't require the user to have explicit permissions for DeleteAlias.
     *
     * > A deleted Lambda function cannot be recovered. Ensure that you specify the correct function name and version before
     * > deleting.
     *
     * To delete Lambda event source mappings that invoke a function, use DeleteEventSourceMapping. For Amazon Web Services
     * services and resources that invoke your function directly, delete the trigger in the service where you originally
     * configured it.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/APIReference/API_DeleteFunction.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#deletefunction
     *
     * @param array{
     *   FunctionName: string,
     *   Qualifier?: string|null,
     *   '@region'?: string|null,
     * }|DeleteFunctionRequest $input
     *
     * @throws InvalidParameterValueException
     * @throws ResourceConflictException
     * @throws ResourceNotFoundException
     * @throws ServiceException
     * @throws TooManyRequestsException
     */
    public function deleteFunction($input): Result
    {
        $input = DeleteFunctionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteFunction', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterValueException' => InvalidParameterValueException::class,
            'ResourceConflictException' => ResourceConflictException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceException' => ServiceException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Returns the version-specific settings of a Lambda function or version. The output includes only options that can vary
     * between versions of a function. To modify these settings, use UpdateFunctionConfiguration.
     *
     * To get all of a function's details, including function-level settings, use GetFunction.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/APIReference/API_GetFunctionConfiguration.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#getfunctionconfiguration
     *
     * @param array{
     *   FunctionName: string,
     *   Qualifier?: string|null,
     *   '@region'?: string|null,
     * }|GetFunctionConfigurationRequest $input
     *
     * @throws InvalidParameterValueException
     * @throws ResourceNotFoundException
     * @throws ServiceException
     * @throws TooManyRequestsException
     */
    public function getFunctionConfiguration($input): FunctionConfiguration
    {
        $input = GetFunctionConfigurationRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetFunctionConfiguration', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterValueException' => InvalidParameterValueException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceException' => ServiceException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new FunctionConfiguration($response);
    }

    /**
     * Invokes a Lambda function. You can invoke a function synchronously (and wait for the response), or asynchronously. By
     * default, Lambda invokes your function synchronously (i.e. the`InvocationType` is `RequestResponse`). To invoke a
     * function asynchronously, set `InvocationType` to `Event`. Lambda passes the `ClientContext` object to your function
     * for synchronous invocations only.
     *
     * For synchronous invocations, the maximum payload size is 6 MB. For asynchronous invocations, the maximum payload size
     * is 1 MB.
     *
     * For synchronous invocation [^1], details about the function response, including errors, are included in the response
     * body and headers. For either invocation type, you can find more information in the execution log [^2] and trace [^3].
     *
     * When an error occurs, your function may be invoked multiple times. Retry behavior varies by error type, client, event
     * source, and invocation type. For example, if you invoke a function asynchronously and it returns an error, Lambda
     * executes the function up to two more times. For more information, see Error handling and automatic retries in Lambda
     * [^4].
     *
     * For asynchronous invocation [^5], Lambda adds events to a queue before sending them to your function. If your
     * function does not have enough capacity to keep up with the queue, events may be lost. Occasionally, your function may
     * receive the same event multiple times, even if no error occurs. To retain events that were not processed, configure
     * your function with a dead-letter queue [^6].
     *
     * The status code in the API response doesn't reflect function errors. Error codes are reserved for errors that prevent
     * your function from executing, such as permissions errors, quota [^7] errors, or issues with your function's code and
     * configuration. For example, Lambda returns `TooManyRequestsException` if running the function would cause you to
     * exceed a concurrency limit at either the account level (`ConcurrentInvocationLimitExceeded`) or function level
     * (`ReservedFunctionConcurrentInvocationLimitExceeded`).
     *
     * For functions with a long timeout, your client might disconnect during synchronous invocation while it waits for a
     * response. Configure your HTTP client, SDK, firewall, proxy, or operating system to allow for long connections with
     * timeout or keep-alive settings.
     *
     * This operation requires permission for the lambda:InvokeFunction [^8] action. For details on how to set up
     * permissions for cross-account invocations, see Granting function access to other accounts [^9].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-sync.html
     * [^2]: https://docs.aws.amazon.com/lambda/latest/dg/monitoring-functions.html
     * [^3]: https://docs.aws.amazon.com/lambda/latest/dg/lambda-x-ray.html
     * [^4]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-retries.html
     * [^5]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-async.html
     * [^6]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-async.html#invocation-dlq
     * [^7]: https://docs.aws.amazon.com/lambda/latest/dg/gettingstarted-limits.html
     * [^8]: https://docs.aws.amazon.com/IAM/latest/UserGuide/list_awslambda.html
     * [^9]: https://docs.aws.amazon.com/lambda/latest/dg/access-control-resource-based.html#permissions-resource-xaccountinvoke
     *
     * @see https://docs.aws.amazon.com/lambda/latest/APIReference/API_Invoke.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#invoke
     *
     * @param array{
     *   FunctionName: string,
     *   InvocationType?: InvocationType::*|null,
     *   LogType?: LogType::*|null,
     *   ClientContext?: string|null,
     *   Payload?: string|null,
     *   Qualifier?: string|null,
     *   TenantId?: string|null,
     *   '@region'?: string|null,
     * }|InvocationRequest $input
     *
     * @throws EC2AccessDeniedException
     * @throws EC2ThrottledException
     * @throws EC2UnexpectedException
     * @throws EFSIOException
     * @throws EFSMountConnectivityException
     * @throws EFSMountFailureException
     * @throws EFSMountTimeoutException
     * @throws ENILimitReachedException
     * @throws InvalidParameterValueException
     * @throws InvalidRequestContentException
     * @throws InvalidRuntimeException
     * @throws InvalidSecurityGroupIDException
     * @throws InvalidSubnetIDException
     * @throws InvalidZipFileException
     * @throws KMSAccessDeniedException
     * @throws KMSDisabledException
     * @throws KMSInvalidStateException
     * @throws KMSNotFoundException
     * @throws RecursiveInvocationException
     * @throws RequestTooLargeException
     * @throws ResourceConflictException
     * @throws ResourceNotFoundException
     * @throws ResourceNotReadyException
     * @throws SerializedRequestEntityTooLargeException
     * @throws ServiceException
     * @throws SnapStartException
     * @throws SnapStartNotReadyException
     * @throws SnapStartTimeoutException
     * @throws SubnetIPAddressLimitReachedException
     * @throws TooManyRequestsException
     * @throws UnsupportedMediaTypeException
     */
    public function invoke($input): InvocationResponse
    {
        $input = InvocationRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Invoke', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'EC2AccessDeniedException' => EC2AccessDeniedException::class,
            'EC2ThrottledException' => EC2ThrottledException::class,
            'EC2UnexpectedException' => EC2UnexpectedException::class,
            'EFSIOException' => EFSIOException::class,
            'EFSMountConnectivityException' => EFSMountConnectivityException::class,
            'EFSMountFailureException' => EFSMountFailureException::class,
            'EFSMountTimeoutException' => EFSMountTimeoutException::class,
            'ENILimitReachedException' => ENILimitReachedException::class,
            'InvalidParameterValueException' => InvalidParameterValueException::class,
            'InvalidRequestContentException' => InvalidRequestContentException::class,
            'InvalidRuntimeException' => InvalidRuntimeException::class,
            'InvalidSecurityGroupIDException' => InvalidSecurityGroupIDException::class,
            'InvalidSubnetIDException' => InvalidSubnetIDException::class,
            'InvalidZipFileException' => InvalidZipFileException::class,
            'KMSAccessDeniedException' => KMSAccessDeniedException::class,
            'KMSDisabledException' => KMSDisabledException::class,
            'KMSInvalidStateException' => KMSInvalidStateException::class,
            'KMSNotFoundException' => KMSNotFoundException::class,
            'RecursiveInvocationException' => RecursiveInvocationException::class,
            'RequestTooLargeException' => RequestTooLargeException::class,
            'ResourceConflictException' => ResourceConflictException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ResourceNotReadyException' => ResourceNotReadyException::class,
            'SerializedRequestEntityTooLargeException' => SerializedRequestEntityTooLargeException::class,
            'ServiceException' => ServiceException::class,
            'SnapStartException' => SnapStartException::class,
            'SnapStartNotReadyException' => SnapStartNotReadyException::class,
            'SnapStartTimeoutException' => SnapStartTimeoutException::class,
            'SubnetIPAddressLimitReachedException' => SubnetIPAddressLimitReachedException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnsupportedMediaTypeException' => UnsupportedMediaTypeException::class,
        ]]));

        return new InvocationResponse($response);
    }

    /**
     * Returns a list of Lambda functions, with the version-specific configuration of each. Lambda returns up to 50
     * functions per call.
     *
     * Set `FunctionVersion` to `ALL` to include all published versions of each function in addition to the unpublished
     * version.
     *
     * > The `ListFunctions` operation returns a subset of the FunctionConfiguration fields. To get the additional fields
     * > (State, StateReasonCode, StateReason, LastUpdateStatus, LastUpdateStatusReason, LastUpdateStatusReasonCode,
     * > RuntimeVersionConfig) for a function or version, use GetFunction.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/APIReference/API_ListFunctions.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#listfunctions
     *
     * @param array{
     *   MasterRegion?: string|null,
     *   FunctionVersion?: FunctionVersion::*|null,
     *   Marker?: string|null,
     *   MaxItems?: int|null,
     *   '@region'?: string|null,
     * }|ListFunctionsRequest $input
     *
     * @throws InvalidParameterValueException
     * @throws ServiceException
     * @throws TooManyRequestsException
     */
    public function listFunctions($input = []): ListFunctionsResponse
    {
        $input = ListFunctionsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListFunctions', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterValueException' => InvalidParameterValueException::class,
            'ServiceException' => ServiceException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new ListFunctionsResponse($response, $this, $input);
    }

    /**
     * Lists the versions of an Lambda layer [^1]. Versions that have been deleted aren't listed. Specify a runtime
     * identifier [^2] to list only versions that indicate that they're compatible with that runtime. Specify a compatible
     * architecture to include only layer versions that are compatible with that architecture.
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-layers.html
     * [^2]: https://docs.aws.amazon.com/lambda/latest/dg/lambda-runtimes.html
     *
     * @see https://docs.aws.amazon.com/lambda/latest/APIReference/API_ListLayerVersions.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#listlayerversions
     *
     * @param array{
     *   CompatibleRuntime?: Runtime::*|null,
     *   LayerName: string,
     *   Marker?: string|null,
     *   MaxItems?: int|null,
     *   CompatibleArchitecture?: Architecture::*|null,
     *   '@region'?: string|null,
     * }|ListLayerVersionsRequest $input
     *
     * @throws InvalidParameterValueException
     * @throws ResourceNotFoundException
     * @throws ServiceException
     * @throws TooManyRequestsException
     */
    public function listLayerVersions($input): ListLayerVersionsResponse
    {
        $input = ListLayerVersionsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListLayerVersions', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterValueException' => InvalidParameterValueException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceException' => ServiceException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new ListLayerVersionsResponse($response, $this, $input);
    }

    /**
     * Returns a list of versions [^1], with the version-specific configuration of each. Lambda returns up to 50 versions
     * per call.
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/versioning-aliases.html
     *
     * @see https://docs.aws.amazon.com/lambda/latest/APIReference/API_ListVersionsByFunction.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#listversionsbyfunction
     *
     * @param array{
     *   FunctionName: string,
     *   Marker?: string|null,
     *   MaxItems?: int|null,
     *   '@region'?: string|null,
     * }|ListVersionsByFunctionRequest $input
     *
     * @throws InvalidParameterValueException
     * @throws ResourceNotFoundException
     * @throws ServiceException
     * @throws TooManyRequestsException
     */
    public function listVersionsByFunction($input): ListVersionsByFunctionResponse
    {
        $input = ListVersionsByFunctionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListVersionsByFunction', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterValueException' => InvalidParameterValueException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceException' => ServiceException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new ListVersionsByFunctionResponse($response, $this, $input);
    }

    /**
     * Creates an Lambda layer [^1] from a ZIP archive. Each time you call `PublishLayerVersion` with the same layer name, a
     * new version is created.
     *
     * Add layers to your function with CreateFunction or UpdateFunctionConfiguration.
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-layers.html
     *
     * @see https://docs.aws.amazon.com/lambda/latest/APIReference/API_PublishLayerVersion.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#publishlayerversion
     *
     * @param array{
     *   LayerName: string,
     *   Description?: string|null,
     *   Content: LayerVersionContentInput|array,
     *   CompatibleRuntimes?: array<Runtime::*>|null,
     *   LicenseInfo?: string|null,
     *   CompatibleArchitectures?: array<Architecture::*>|null,
     *   '@region'?: string|null,
     * }|PublishLayerVersionRequest $input
     *
     * @throws CodeStorageExceededException
     * @throws InvalidParameterValueException
     * @throws ResourceNotFoundException
     * @throws ServiceException
     * @throws TooManyRequestsException
     */
    public function publishLayerVersion($input): PublishLayerVersionResponse
    {
        $input = PublishLayerVersionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PublishLayerVersion', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'CodeStorageExceededException' => CodeStorageExceededException::class,
            'InvalidParameterValueException' => InvalidParameterValueException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceException' => ServiceException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new PublishLayerVersionResponse($response);
    }

    /**
     * Modify the version-specific settings of a Lambda function.
     *
     * When you update a function, Lambda provisions an instance of the function and its supporting resources. If your
     * function connects to a VPC, this process can take a minute. During this time, you can't modify the function, but you
     * can still invoke it. The `LastUpdateStatus`, `LastUpdateStatusReason`, and `LastUpdateStatusReasonCode` fields in the
     * response from GetFunctionConfiguration indicate when the update is complete and the function is processing events
     * with the new configuration. For more information, see Lambda function states [^1].
     *
     * These settings can vary between versions of a function and are locked when you publish a version. You can't modify
     * the configuration of a published version, only the unpublished version.
     *
     * To configure function concurrency, use PutFunctionConcurrency. To grant invoke permissions to an Amazon Web Services
     * account or Amazon Web Services service, use AddPermission.
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/functions-states.html
     *
     * @see https://docs.aws.amazon.com/lambda/latest/APIReference/API_UpdateFunctionConfiguration.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#updatefunctionconfiguration
     *
     * @param array{
     *   FunctionName: string,
     *   Role?: string|null,
     *   Handler?: string|null,
     *   Description?: string|null,
     *   Timeout?: int|null,
     *   MemorySize?: int|null,
     *   VpcConfig?: VpcConfig|array|null,
     *   Environment?: Environment|array|null,
     *   Runtime?: Runtime::*|null,
     *   DeadLetterConfig?: DeadLetterConfig|array|null,
     *   KMSKeyArn?: string|null,
     *   TracingConfig?: TracingConfig|array|null,
     *   RevisionId?: string|null,
     *   Layers?: string[]|null,
     *   FileSystemConfigs?: array<FileSystemConfig|array>|null,
     *   ImageConfig?: ImageConfig|array|null,
     *   EphemeralStorage?: EphemeralStorage|array|null,
     *   SnapStart?: SnapStart|array|null,
     *   LoggingConfig?: LoggingConfig|array|null,
     *   '@region'?: string|null,
     * }|UpdateFunctionConfigurationRequest $input
     *
     * @throws CodeSigningConfigNotFoundException
     * @throws CodeVerificationFailedException
     * @throws InvalidCodeSignatureException
     * @throws InvalidParameterValueException
     * @throws PreconditionFailedException
     * @throws ResourceConflictException
     * @throws ResourceNotFoundException
     * @throws ServiceException
     * @throws TooManyRequestsException
     */
    public function updateFunctionConfiguration($input): FunctionConfiguration
    {
        $input = UpdateFunctionConfigurationRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateFunctionConfiguration', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'CodeSigningConfigNotFoundException' => CodeSigningConfigNotFoundException::class,
            'CodeVerificationFailedException' => CodeVerificationFailedException::class,
            'InvalidCodeSignatureException' => InvalidCodeSignatureException::class,
            'InvalidParameterValueException' => InvalidParameterValueException::class,
            'PreconditionFailedException' => PreconditionFailedException::class,
            'ResourceConflictException' => ResourceConflictException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceException' => ServiceException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new FunctionConfiguration($response);
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
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://lambda.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'lambda',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
            case 'us-isob-west-1':
                return [
                    'endpoint' => "https://lambda.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'lambda',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://lambda.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'lambda',
                    'signVersions' => ['v4'],
                ];
            case 'eu-isoe-west-1':
                return [
                    'endpoint' => 'https://lambda.eu-isoe-west-1.cloud.adc-e.uk',
                    'signRegion' => 'eu-isoe-west-1',
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
