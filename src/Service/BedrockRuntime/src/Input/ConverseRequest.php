<?php

namespace AsyncAws\BedrockRuntime\Input;

use AsyncAws\BedrockRuntime\ValueObject\Document;
use AsyncAws\BedrockRuntime\ValueObject\GuardrailConfiguration;
use AsyncAws\BedrockRuntime\ValueObject\InferenceConfiguration;
use AsyncAws\BedrockRuntime\ValueObject\Message;
use AsyncAws\BedrockRuntime\ValueObject\PerformanceConfiguration;
use AsyncAws\BedrockRuntime\ValueObject\PromptVariableValues;
use AsyncAws\BedrockRuntime\ValueObject\SystemContentBlock;
use AsyncAws\BedrockRuntime\ValueObject\Tool;
use AsyncAws\BedrockRuntime\ValueObject\ToolConfiguration;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ConverseRequest extends Input
{
    /**
     * Specifies the model or throughput with which to run inference, or the prompt resource to use in inference. The value
     * depends on the resource that you use:
     *
     * - If you use a base model, specify the model ID or its ARN. For a list of model IDs for base models, see Amazon
     *   Bedrock base model IDs (on-demand throughput) [^1] in the Amazon Bedrock User Guide.
     * - If you use an inference profile, specify the inference profile ID or its ARN. For a list of inference profile IDs,
     *   see Supported Regions and models for cross-region inference [^2] in the Amazon Bedrock User Guide.
     * - If you use a provisioned model, specify the ARN of the Provisioned Throughput. For more information, see Run
     *   inference using a Provisioned Throughput [^3] in the Amazon Bedrock User Guide.
     * - If you use a custom model, first purchase Provisioned Throughput for it. Then specify the ARN of the resulting
     *   provisioned model. For more information, see Use a custom model in Amazon Bedrock [^4] in the Amazon Bedrock User
     *   Guide.
     * - To include a prompt that was defined in Prompt management [^5], specify the ARN of the prompt version to use.
     *
     * The Converse API doesn't support imported models [^6].
     *
     * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/model-ids.html#model-ids-arns
     * [^2]: https://docs.aws.amazon.com/bedrock/latest/userguide/cross-region-inference-support.html
     * [^3]: https://docs.aws.amazon.com/bedrock/latest/userguide/prov-thru-use.html
     * [^4]: https://docs.aws.amazon.com/bedrock/latest/userguide/model-customization-use.html
     * [^5]: https://docs.aws.amazon.com/bedrock/latest/userguide/prompt-management.html
     * [^6]: https://docs.aws.amazon.com/bedrock/latest/userguide/model-customization-import-model.html
     *
     * @required
     *
     * @var string|null
     */
    private $modelId;

    /**
     * The messages that you want to send to the model.
     *
     * @var Message[]|null
     */
    private $messages;

    /**
     * A prompt that provides instructions or context to the model about the task it should perform, or the persona it
     * should adopt during the conversation.
     *
     * @var SystemContentBlock[]|null
     */
    private $system;

    /**
     * Inference parameters to pass to the model. `Converse` and `ConverseStream` support a base set of inference
     * parameters. If you need to pass additional parameters that the model supports, use the `additionalModelRequestFields`
     * request field.
     *
     * @var InferenceConfiguration|null
     */
    private $inferenceConfig;

    /**
     * Configuration information for the tools that the model can use when generating a response.
     *
     * For information about models that support tool use, see Supported models and model features [^1].
     *
     * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/conversation-inference.html#conversation-inference-supported-models-features
     *
     * @var ToolConfiguration|null
     */
    private $toolConfig;

    /**
     * Configuration information for a guardrail that you want to use in the request. If you include `guardContent` blocks
     * in the `content` field in the `messages` field, the guardrail operates only on those messages. If you include no
     * `guardContent` blocks, the guardrail operates on all messages in the request body and in any included prompt
     * resource.
     *
     * @var GuardrailConfiguration|null
     */
    private $guardrailConfig;

    /**
     * Additional inference parameters that the model supports, beyond the base set of inference parameters that `Converse`
     * and `ConverseStream` support in the `inferenceConfig` field. For more information, see Model parameters [^1].
     *
     * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/model-parameters.html
     *
     * @var Document|null
     */
    private $additionalModelRequestFields;

    /**
     * Contains a map of variables in a prompt from Prompt management to objects containing the values to fill in for them
     * when running model invocation. This field is ignored if you don't specify a prompt resource in the `modelId` field.
     *
     * @var array<string, PromptVariableValues>|null
     */
    private $promptVariables;

    /**
     * Additional model parameters field paths to return in the response. `Converse` and `ConverseStream` return the
     * requested fields as a JSON Pointer object in the `additionalModelResponseFields` field. The following is example JSON
     * for `additionalModelResponseFieldPaths`.
     *
     * `[ "/stop_sequence" ]`
     *
     * For information about the JSON Pointer syntax, see the Internet Engineering Task Force (IETF) [^1] documentation.
     *
     * `Converse` and `ConverseStream` reject an empty JSON Pointer or incorrectly structured JSON Pointer with a `400`
     * error code. if the JSON Pointer is valid, but the requested field is not in the model response, it is ignored by
     * `Converse`.
     *
     * [^1]: https://datatracker.ietf.org/doc/html/rfc6901
     *
     * @var string[]|null
     */
    private $additionalModelResponseFieldPaths;

    /**
     * Key-value pairs that you can use to filter invocation logs.
     *
     * @var array<string, string>|null
     */
    private $requestMetadata;

    /**
     * Model performance settings for the request.
     *
     * @var PerformanceConfiguration|null
     */
    private $performanceConfig;

    /**
     * @param array{
     *   modelId?: string,
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
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->modelId = $input['modelId'] ?? null;
        $this->messages = isset($input['messages']) ? array_map([Message::class, 'create'], $input['messages']) : null;
        $this->system = isset($input['system']) ? array_map([SystemContentBlock::class, 'create'], $input['system']) : null;
        $this->inferenceConfig = isset($input['inferenceConfig']) ? InferenceConfiguration::create($input['inferenceConfig']) : null;
        $this->toolConfig = isset($input['toolConfig']) ? ToolConfiguration::create($input['toolConfig']) : null;
        $this->guardrailConfig = isset($input['guardrailConfig']) ? GuardrailConfiguration::create($input['guardrailConfig']) : null;
        $this->additionalModelRequestFields = isset($input['additionalModelRequestFields']) ? Document::create($input['additionalModelRequestFields']) : null;

        if (isset($input['promptVariables'])) {
            $this->promptVariables = [];
            foreach ($input['promptVariables'] as $key => $item) {
                $this->promptVariables[$key] = PromptVariableValues::create($item);
            }
        }
        $this->additionalModelResponseFieldPaths = $input['additionalModelResponseFieldPaths'] ?? null;
        $this->requestMetadata = $input['requestMetadata'] ?? null;
        $this->performanceConfig = isset($input['performanceConfig']) ? PerformanceConfiguration::create($input['performanceConfig']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   modelId?: string,
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
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAdditionalModelRequestFields(): ?Document
    {
        return $this->additionalModelRequestFields;
    }

    /**
     * @return string[]
     */
    public function getAdditionalModelResponseFieldPaths(): array
    {
        return $this->additionalModelResponseFieldPaths ?? [];
    }

    public function getGuardrailConfig(): ?GuardrailConfiguration
    {
        return $this->guardrailConfig;
    }

    public function getInferenceConfig(): ?InferenceConfiguration
    {
        return $this->inferenceConfig;
    }

    /**
     * @return Message[]
     */
    public function getMessages(): array
    {
        return $this->messages ?? [];
    }

    public function getModelId(): ?string
    {
        return $this->modelId;
    }

    public function getPerformanceConfig(): ?PerformanceConfiguration
    {
        return $this->performanceConfig;
    }

    /**
     * @return array<string, PromptVariableValues>
     */
    public function getPromptVariables(): array
    {
        return $this->promptVariables ?? [];
    }

    /**
     * @return array<string, string>
     */
    public function getRequestMetadata(): array
    {
        return $this->requestMetadata ?? [];
    }

    /**
     * @return SystemContentBlock[]
     */
    public function getSystem(): array
    {
        return $this->system ?? [];
    }

    public function getToolConfig(): ?ToolConfiguration
    {
        return $this->toolConfig;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->modelId) {
            throw new InvalidArgument(\sprintf('Missing parameter "modelId" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['modelId'] = $v;
        $uriString = '/model/' . rawurlencode($uri['modelId']) . '/converse';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAdditionalModelRequestFields(?Document $value): self
    {
        $this->additionalModelRequestFields = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setAdditionalModelResponseFieldPaths(array $value): self
    {
        $this->additionalModelResponseFieldPaths = $value;

        return $this;
    }

    public function setGuardrailConfig(?GuardrailConfiguration $value): self
    {
        $this->guardrailConfig = $value;

        return $this;
    }

    public function setInferenceConfig(?InferenceConfiguration $value): self
    {
        $this->inferenceConfig = $value;

        return $this;
    }

    /**
     * @param Message[] $value
     */
    public function setMessages(array $value): self
    {
        $this->messages = $value;

        return $this;
    }

    public function setModelId(?string $value): self
    {
        $this->modelId = $value;

        return $this;
    }

    public function setPerformanceConfig(?PerformanceConfiguration $value): self
    {
        $this->performanceConfig = $value;

        return $this;
    }

    /**
     * @param array<string, PromptVariableValues> $value
     */
    public function setPromptVariables(array $value): self
    {
        $this->promptVariables = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setRequestMetadata(array $value): self
    {
        $this->requestMetadata = $value;

        return $this;
    }

    /**
     * @param SystemContentBlock[] $value
     */
    public function setSystem(array $value): self
    {
        $this->system = $value;

        return $this;
    }

    public function setToolConfig(?ToolConfiguration $value): self
    {
        $this->toolConfig = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null !== $v = $this->messages) {
            $index = -1;
            $payload['messages'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['messages'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->system) {
            $index = -1;
            $payload['system'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['system'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->inferenceConfig) {
            $payload['inferenceConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->toolConfig) {
            $payload['toolConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->guardrailConfig) {
            $payload['guardrailConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->additionalModelRequestFields) {
            $payload['additionalModelRequestFields'] = $v->requestBody();
        }
        if (null !== $v = $this->promptVariables) {
            if (empty($v)) {
                $payload['promptVariables'] = new \stdClass();
            } else {
                $payload['promptVariables'] = [];
                foreach ($v as $name => $mv) {
                    $payload['promptVariables'][$name] = $mv->requestBody();
                }
            }
        }
        if (null !== $v = $this->additionalModelResponseFieldPaths) {
            $index = -1;
            $payload['additionalModelResponseFieldPaths'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['additionalModelResponseFieldPaths'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->requestMetadata) {
            if (empty($v)) {
                $payload['requestMetadata'] = new \stdClass();
            } else {
                $payload['requestMetadata'] = [];
                foreach ($v as $name => $mv) {
                    $payload['requestMetadata'][$name] = $mv;
                }
            }
        }
        if (null !== $v = $this->performanceConfig) {
            $payload['performanceConfig'] = $v->requestBody();
        }

        return $payload;
    }
}
