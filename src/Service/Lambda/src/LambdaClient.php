<?php

namespace AsyncAws\Lambda;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Lambda\Input\AddLayerVersionPermissionRequest;
use AsyncAws\Lambda\Input\InvocationRequest;
use AsyncAws\Lambda\Input\ListLayerVersionsRequest;
use AsyncAws\Lambda\Input\PublishLayerVersionRequest;
use AsyncAws\Lambda\Result\AddLayerVersionPermissionResponse;
use AsyncAws\Lambda\Result\InvocationResponse;
use AsyncAws\Lambda\Result\ListLayerVersionsResponse;
use AsyncAws\Lambda\Result\PublishLayerVersionResponse;

class LambdaClient extends AbstractApi
{
    /**
     * Adds permissions to the resource-based policy of a version of an AWS Lambda layer. Use this action to grant layer
     * usage permission to other accounts. You can grant permission to a single account, all AWS accounts, or all accounts
     * in an organization.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/dg/configuration-layers.html
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
     * }|AddLayerVersionPermissionRequest $input
     */
    public function addLayerVersionPermission($input): AddLayerVersionPermissionResponse
    {
        $response = $this->getResponse(AddLayerVersionPermissionRequest::create($input)->request());

        return new AddLayerVersionPermissionResponse($response, $this->httpClient);
    }

    /**
     * Invokes a Lambda function. You can invoke a function synchronously (and wait for the response), or asynchronously. To
     * invoke a function asynchronously, set `InvocationType` to `Event`.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#invoke
     *
     * @param array{
     *   FunctionName: string,
     *   InvocationType?: \AsyncAws\Lambda\Enum\InvocationType::*,
     *   LogType?: \AsyncAws\Lambda\Enum\LogType::*,
     *   ClientContext?: string,
     *   Payload?: string,
     *   Qualifier?: string,
     * }|InvocationRequest $input
     */
    public function invoke($input): InvocationResponse
    {
        $response = $this->getResponse(InvocationRequest::create($input)->request());

        return new InvocationResponse($response, $this->httpClient);
    }

    /**
     * Lists the versions of an AWS Lambda layer. Versions that have been deleted aren't listed. Specify a runtime
     * identifier to list only versions that indicate that they're compatible with that runtime.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/dg/configuration-layers.html
     * @see https://docs.aws.amazon.com/lambda/latest/dg/lambda-runtimes.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#listlayerversions
     *
     * @param array{
     *   CompatibleRuntime?: \AsyncAws\Lambda\Enum\Runtime::*,
     *   LayerName: string,
     *   Marker?: string,
     *   MaxItems?: int,
     * }|ListLayerVersionsRequest $input
     */
    public function listLayerVersions($input): ListLayerVersionsResponse
    {
        $input = ListLayerVersionsRequest::create($input);
        $response = $this->getResponse($input->request());

        return new ListLayerVersionsResponse($response, $this->httpClient, $this, $input);
    }

    /**
     * Creates an AWS Lambda layer from a ZIP archive. Each time you call `PublishLayerVersion` with the same layer name, a
     * new version is created.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/dg/configuration-layers.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-lambda-2015-03-31.html#publishlayerversion
     *
     * @param array{
     *   LayerName: string,
     *   Description?: string,
     *   Content: \AsyncAws\Lambda\ValueObject\LayerVersionContentInput|array,
     *   CompatibleRuntimes?: list<\AsyncAws\Lambda\Enum\Runtime::*>,
     *   LicenseInfo?: string,
     * }|PublishLayerVersionRequest $input
     */
    public function publishLayerVersion($input): PublishLayerVersionResponse
    {
        $response = $this->getResponse(PublishLayerVersionRequest::create($input)->request());

        return new PublishLayerVersionResponse($response, $this->httpClient);
    }

    protected function getServiceCode(): string
    {
        return 'lambda';
    }

    protected function getSignatureScopeName(): string
    {
        return 'lambda';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
