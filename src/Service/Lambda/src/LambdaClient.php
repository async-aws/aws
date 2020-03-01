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
        $input = AddLayerVersionPermissionRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

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
     *   InvocationType?: \AsyncAws\Lambda\Enum\InvocationType::EVENT|\AsyncAws\Lambda\Enum\InvocationType::REQUEST_RESPONSE|\AsyncAws\Lambda\Enum\InvocationType::DRY_RUN,
     *   LogType?: \AsyncAws\Lambda\Enum\LogType::NONE|\AsyncAws\Lambda\Enum\LogType::TAIL,
     *   ClientContext?: string,
     *   Payload?: string,
     *   Qualifier?: string,
     * }|InvocationRequest $input
     */
    public function invoke($input): InvocationResponse
    {
        $input = InvocationRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

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
     *   CompatibleRuntime?: \AsyncAws\Lambda\Enum\Runtime::NODEJS|\AsyncAws\Lambda\Enum\Runtime::NODEJS_4_3|\AsyncAws\Lambda\Enum\Runtime::NODEJS_6_10|\AsyncAws\Lambda\Enum\Runtime::NODEJS_8_10|\AsyncAws\Lambda\Enum\Runtime::NODEJS_10_X|\AsyncAws\Lambda\Enum\Runtime::NODEJS_12_X|\AsyncAws\Lambda\Enum\Runtime::JAVA_8|\AsyncAws\Lambda\Enum\Runtime::JAVA_11|\AsyncAws\Lambda\Enum\Runtime::PYTHON_2_7|\AsyncAws\Lambda\Enum\Runtime::PYTHON_3_6|\AsyncAws\Lambda\Enum\Runtime::PYTHON_3_7|\AsyncAws\Lambda\Enum\Runtime::PYTHON_3_8|\AsyncAws\Lambda\Enum\Runtime::DOTNETCORE_1_0|\AsyncAws\Lambda\Enum\Runtime::DOTNETCORE_2_0|\AsyncAws\Lambda\Enum\Runtime::DOTNETCORE_2_1|\AsyncAws\Lambda\Enum\Runtime::NODEJS_4_3_EDGE|\AsyncAws\Lambda\Enum\Runtime::GO_1_X|\AsyncAws\Lambda\Enum\Runtime::RUBY_2_5|\AsyncAws\Lambda\Enum\Runtime::PROVIDED,
     *   LayerName: string,
     *   Marker?: string,
     *   MaxItems?: int,
     * }|ListLayerVersionsRequest $input
     */
    public function listLayerVersions($input): ListLayerVersionsResponse
    {
        $input = ListLayerVersionsRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'GET',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

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
     *   Content: \AsyncAws\Lambda\Input\LayerVersionContentInput|array,
     *   CompatibleRuntimes?: string[],
     *   LicenseInfo?: string,
     * }|PublishLayerVersionRequest $input
     */
    public function publishLayerVersion($input): PublishLayerVersionResponse
    {
        $input = PublishLayerVersionRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

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
