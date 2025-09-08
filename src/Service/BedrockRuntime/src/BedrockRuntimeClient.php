<?php

namespace AsyncAws\BedrockRuntime;

use AsyncAws\BedrockRuntime\Enum\PerformanceConfigLatency;
use AsyncAws\BedrockRuntime\Enum\Trace;
use AsyncAws\BedrockRuntime\Exception\AccessDeniedException;
use AsyncAws\BedrockRuntime\Exception\InternalServerException;
use AsyncAws\BedrockRuntime\Exception\ModelErrorException;
use AsyncAws\BedrockRuntime\Exception\ModelNotReadyException;
use AsyncAws\BedrockRuntime\Exception\ModelTimeoutException;
use AsyncAws\BedrockRuntime\Exception\ResourceNotFoundException;
use AsyncAws\BedrockRuntime\Exception\ServiceQuotaExceededException;
use AsyncAws\BedrockRuntime\Exception\ServiceUnavailableException;
use AsyncAws\BedrockRuntime\Exception\ThrottlingException;
use AsyncAws\BedrockRuntime\Exception\ValidationException;
use AsyncAws\BedrockRuntime\Input\InvokeModelRequest;
use AsyncAws\BedrockRuntime\Result\InvokeModelResponse;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;

class BedrockRuntimeClient extends AbstractApi
{
    /**
     * Invokes the specified Amazon Bedrock model to run inference using the prompt and inference parameters provided in the
     * request body. You use model inference to generate text, images, and embeddings.
     *
     * For example code, see *Invoke model code examples* in the *Amazon Bedrock User Guide*.
     *
     * This operation requires permission for the `bedrock:InvokeModel` action.
     *
     * ! To deny all inference access to resources that you specify in the modelId field, you need to deny access to the
     * ! `bedrock:InvokeModel` and `bedrock:InvokeModelWithResponseStream` actions. Doing this also denies access to the
     * ! resource through the Converse API actions (Converse [^1] and ConverseStream [^2]). For more information see Deny
     * ! access for inference on specific models [^3].
     *
     * For troubleshooting some of the common errors you might encounter when using the `InvokeModel` API, see
     * Troubleshooting Amazon Bedrock API Error Codes [^4] in the Amazon Bedrock User Guide
     *
     * [^1]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_Converse.html
     * [^2]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_ConverseStream.html
     * [^3]: https://docs.aws.amazon.com/bedrock/latest/userguide/security_iam_id-based-policy-examples.html#security_iam_id-based-policy-examples-deny-inference
     * [^4]: https://docs.aws.amazon.com/bedrock/latest/userguide/troubleshooting-api-error-codes.html
     *
     * @see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_InvokeModel.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-bedrock-runtime-2023-09-30.html#invokemodel
     *
     * @param array{
     *   body?: string|null,
     *   contentType?: string|null,
     *   accept?: string|null,
     *   modelId: string,
     *   trace?: Trace::*|null,
     *   guardrailIdentifier?: string|null,
     *   guardrailVersion?: string|null,
     *   performanceConfigLatency?: PerformanceConfigLatency::*|null,
     *   '@region'?: string|null,
     * }|InvokeModelRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws ModelErrorException
     * @throws ModelNotReadyException
     * @throws ModelTimeoutException
     * @throws ResourceNotFoundException
     * @throws ServiceQuotaExceededException
     * @throws ServiceUnavailableException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function invokeModel($input): InvokeModelResponse
    {
        $input = InvokeModelRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'InvokeModel', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'ModelErrorException' => ModelErrorException::class,
            'ModelNotReadyException' => ModelNotReadyException::class,
            'ModelTimeoutException' => ModelTimeoutException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new InvokeModelResponse($response);
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

        return [
            'endpoint' => "https://bedrock-runtime.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'bedrock',
            'signVersions' => ['v4'],
        ];
    }
}
