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
use AsyncAws\BedrockRuntime\Input\ConverseRequest;
use AsyncAws\BedrockRuntime\Input\InvokeModelRequest;
use AsyncAws\BedrockRuntime\Result\ConverseResponse;
use AsyncAws\BedrockRuntime\Result\InvokeModelResponse;
use AsyncAws\BedrockRuntime\ValueObject\Document;
use AsyncAws\BedrockRuntime\ValueObject\GuardrailConfiguration;
use AsyncAws\BedrockRuntime\ValueObject\InferenceConfiguration;
use AsyncAws\BedrockRuntime\ValueObject\Message;
use AsyncAws\BedrockRuntime\ValueObject\PerformanceConfiguration;
use AsyncAws\BedrockRuntime\ValueObject\PromptVariableValues;
use AsyncAws\BedrockRuntime\ValueObject\SystemContentBlock;
use AsyncAws\BedrockRuntime\ValueObject\ToolConfiguration;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;

class BedrockRuntimeClient extends AbstractApi
{
    /**
     * Sends messages to the specified Amazon Bedrock model. `Converse` provides a consistent interface that works with all
     * models that support messages. This allows you to write code once and use it with different models. If a model has
     * unique inference parameters, you can also pass those unique parameters to the model.
     *
     * Amazon Bedrock doesn't store any text, images, or documents that you provide as content. The data is only used to
     * generate the response.
     *
     * You can submit a prompt by including it in the `messages` field, specifying the `modelId` of a foundation model or
     * inference profile to run inference on it, and including any other fields that are relevant to your use case.
     *
     * You can also submit a prompt from Prompt management by specifying the ARN of the prompt version and including a map
     * of variables to values in the `promptVariables` field. You can append more messages to the prompt by using the
     * `messages` field. If you use a prompt from Prompt management, you can't include the following fields in the request:
     * `additionalModelRequestFields`, `inferenceConfig`, `system`, or `toolConfig`. Instead, these fields must be defined
     * through Prompt management. For more information, see Use a prompt from Prompt management [^1].
     *
     * For information about the Converse API, see *Use the Converse API* in the *Amazon Bedrock User Guide*. To use a
     * guardrail, see *Use a guardrail with the Converse API* in the *Amazon Bedrock User Guide*. To use a tool with a
     * model, see *Tool use (Function calling)* in the *Amazon Bedrock User Guide*
     *
     * For example code, see *Converse API examples* in the *Amazon Bedrock User Guide*.
     *
     * This operation requires permission for the `bedrock:InvokeModel` action.
     *
     * ! To deny all inference access to resources that you specify in the modelId field, you need to deny access to the
     * ! `bedrock:InvokeModel` and `bedrock:InvokeModelWithResponseStream` actions. Doing this also denies access to the
     * ! resource through the base inference actions (InvokeModel [^2] and InvokeModelWithResponseStream [^3]). For more
     * ! information see Deny access for inference on specific models [^4].
     *
     * For troubleshooting some of the common errors you might encounter when using the `Converse` API, see Troubleshooting
     * Amazon Bedrock API Error Codes [^5] in the Amazon Bedrock User Guide
     *
     * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/prompt-management-use.html
     * [^2]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_InvokeModel.html
     * [^3]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_InvokeModelWithResponseStream.html
     * [^4]: https://docs.aws.amazon.com/bedrock/latest/userguide/security_iam_id-based-policy-examples.html#security_iam_id-based-policy-examples-deny-inference
     * [^5]: https://docs.aws.amazon.com/bedrock/latest/userguide/troubleshooting-api-error-codes.html
     *
     * @see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_Converse.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-bedrock-runtime-2023-09-30.html#converse
     *
     * @param array{
     *   modelId: string,
     *   messages?: null|array<Message|array>,
     *   system?: null|array<SystemContentBlock|array>,
     *   inferenceConfig?: null|InferenceConfiguration|array,
     *   toolConfig?: null|ToolConfiguration|array,
     *   guardrailConfig?: null|GuardrailConfiguration|array,
     *   additionalModelRequestFields?: null|Document|array,
     *   promptVariables?: null|array<string, PromptVariableValues|array>,
     *   additionalModelResponseFieldPaths?: null|string[],
     *   requestMetadata?: null|array<string, string>,
     *   performanceConfig?: null|PerformanceConfiguration|array,
     *   '@region'?: string|null,
     * }|ConverseRequest $input
     *
     * @throws AccessDeniedException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     * @throws ModelTimeoutException
     * @throws InternalServerException
     * @throws ServiceUnavailableException
     * @throws ValidationException
     * @throws ModelNotReadyException
     * @throws ModelErrorException
     */
    public function converse($input): ConverseResponse
    {
        $input = ConverseRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Converse', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ModelTimeoutException' => ModelTimeoutException::class,
            'InternalServerException' => InternalServerException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'ValidationException' => ValidationException::class,
            'ModelNotReadyException' => ModelNotReadyException::class,
            'ModelErrorException' => ModelErrorException::class,
        ]]));

        return new ConverseResponse($response);
    }

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
