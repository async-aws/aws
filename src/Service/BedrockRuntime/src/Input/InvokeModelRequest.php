<?php

namespace AsyncAws\BedrockRuntime\Input;

use AsyncAws\BedrockRuntime\Enum\PerformanceConfigLatency;
use AsyncAws\BedrockRuntime\Enum\ServiceTierType;
use AsyncAws\BedrockRuntime\Enum\Trace;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class InvokeModelRequest extends Input
{
    /**
     * The prompt and inference parameters in the format specified in the `contentType` in the header. You must provide the
     * body in JSON format. To see the format and content of the request and response bodies for different models, refer to
     * Inference parameters [^1]. For more information, see Run inference [^2] in the Bedrock User Guide.
     *
     * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/model-parameters.html
     * [^2]: https://docs.aws.amazon.com/bedrock/latest/userguide/api-methods-run.html
     *
     * @var string|null
     */
    private $body;

    /**
     * The MIME type of the input data in the request. You must specify `application/json`.
     *
     * @var string|null
     */
    private $contentType;

    /**
     * The desired MIME type of the inference body in the response. The default value is `application/json`.
     *
     * @var string|null
     */
    private $accept;

    /**
     * The unique identifier of the model to invoke to run inference.
     *
     * The `modelId` to provide depends on the type of model or throughput that you use:
     *
     * - If you use a base model, specify the model ID or its ARN. For a list of model IDs for base models, see Amazon
     *   Bedrock base model IDs (on-demand throughput) [^1] in the Amazon Bedrock User Guide.
     * - If you use an inference profile, specify the inference profile ID or its ARN. For a list of inference profile IDs,
     *   see Supported Regions and models for cross-region inference [^2] in the Amazon Bedrock User Guide.
     * - If you use a provisioned model, specify the ARN of the Provisioned Throughput. For more information, see Run
     *   inference using a Provisioned Throughput [^3] in the Amazon Bedrock User Guide.
     * - If you use a custom model, specify the ARN of the custom model deployment (for on-demand inference) or the ARN of
     *   your provisioned model (for Provisioned Throughput). For more information, see Use a custom model in Amazon Bedrock
     *   [^4] in the Amazon Bedrock User Guide.
     * - If you use an imported model [^5], specify the ARN of the imported model. You can get the model ARN from a
     *   successful call to CreateModelImportJob [^6] or from the Imported models page in the Amazon Bedrock console.
     *
     * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/model-ids.html#model-ids-arns
     * [^2]: https://docs.aws.amazon.com/bedrock/latest/userguide/cross-region-inference-support.html
     * [^3]: https://docs.aws.amazon.com/bedrock/latest/userguide/prov-thru-use.html
     * [^4]: https://docs.aws.amazon.com/bedrock/latest/userguide/model-customization-use.html
     * [^5]: https://docs.aws.amazon.com/bedrock/latest/userguide/model-customization-import-model.html
     * [^6]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_CreateModelImportJob.html
     *
     * @required
     *
     * @var string|null
     */
    private $modelId;

    /**
     * Specifies whether to enable or disable the Bedrock trace. If enabled, you can see the full Bedrock trace.
     *
     * @var Trace::*|null
     */
    private $trace;

    /**
     * The unique identifier of the guardrail that you want to use. If you don't provide a value, no guardrail is applied to
     * the invocation.
     *
     * An error will be thrown in the following situations.
     *
     * - You don't provide a guardrail identifier but you specify the `amazon-bedrock-guardrailConfig` field in the request
     *   body.
     * - You enable the guardrail but the `contentType` isn't `application/json`.
     * - You provide a guardrail identifier, but `guardrailVersion` isn't specified.
     *
     * @var string|null
     */
    private $guardrailIdentifier;

    /**
     * The version number for the guardrail. The value can also be `DRAFT`.
     *
     * @var string|null
     */
    private $guardrailVersion;

    /**
     * Model performance settings for the request.
     *
     * @var PerformanceConfigLatency::*|null
     */
    private $performanceConfigLatency;

    /**
     * Specifies the processing tier type used for serving the request.
     *
     * @var ServiceTierType::*|null
     */
    private $serviceTier;

    /**
     * @param array{
     *   body?: string|null,
     *   contentType?: string|null,
     *   accept?: string|null,
     *   modelId?: string,
     *   trace?: Trace::*|null,
     *   guardrailIdentifier?: string|null,
     *   guardrailVersion?: string|null,
     *   performanceConfigLatency?: PerformanceConfigLatency::*|null,
     *   serviceTier?: ServiceTierType::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->body = $input['body'] ?? null;
        $this->contentType = $input['contentType'] ?? null;
        $this->accept = $input['accept'] ?? null;
        $this->modelId = $input['modelId'] ?? null;
        $this->trace = $input['trace'] ?? null;
        $this->guardrailIdentifier = $input['guardrailIdentifier'] ?? null;
        $this->guardrailVersion = $input['guardrailVersion'] ?? null;
        $this->performanceConfigLatency = $input['performanceConfigLatency'] ?? null;
        $this->serviceTier = $input['serviceTier'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   body?: string|null,
     *   contentType?: string|null,
     *   accept?: string|null,
     *   modelId?: string,
     *   trace?: Trace::*|null,
     *   guardrailIdentifier?: string|null,
     *   guardrailVersion?: string|null,
     *   performanceConfigLatency?: PerformanceConfigLatency::*|null,
     *   serviceTier?: ServiceTierType::*|null,
     *   '@region'?: string|null,
     * }|InvokeModelRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccept(): ?string
    {
        return $this->accept;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function getGuardrailIdentifier(): ?string
    {
        return $this->guardrailIdentifier;
    }

    public function getGuardrailVersion(): ?string
    {
        return $this->guardrailVersion;
    }

    public function getModelId(): ?string
    {
        return $this->modelId;
    }

    /**
     * @return PerformanceConfigLatency::*|null
     */
    public function getPerformanceConfigLatency(): ?string
    {
        return $this->performanceConfigLatency;
    }

    /**
     * @return ServiceTierType::*|null
     */
    public function getServiceTier(): ?string
    {
        return $this->serviceTier;
    }

    /**
     * @return Trace::*|null
     */
    public function getTrace(): ?string
    {
        return $this->trace;
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
        if (null !== $this->contentType) {
            $headers['Content-Type'] = $this->contentType;
        }
        if (null !== $this->accept) {
            $headers['Accept'] = $this->accept;
        }
        if (null !== $this->trace) {
            if (!Trace::exists($this->trace)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "trace" for "%s". The value "%s" is not a valid "Trace".', __CLASS__, $this->trace));
            }
            $headers['X-Amzn-Bedrock-Trace'] = $this->trace;
        }
        if (null !== $this->guardrailIdentifier) {
            $headers['X-Amzn-Bedrock-GuardrailIdentifier'] = $this->guardrailIdentifier;
        }
        if (null !== $this->guardrailVersion) {
            $headers['X-Amzn-Bedrock-GuardrailVersion'] = $this->guardrailVersion;
        }
        if (null !== $this->performanceConfigLatency) {
            if (!PerformanceConfigLatency::exists($this->performanceConfigLatency)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "performanceConfigLatency" for "%s". The value "%s" is not a valid "PerformanceConfigLatency".', __CLASS__, $this->performanceConfigLatency));
            }
            $headers['X-Amzn-Bedrock-PerformanceConfig-Latency'] = $this->performanceConfigLatency;
        }
        if (null !== $this->serviceTier) {
            if (!ServiceTierType::exists($this->serviceTier)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "serviceTier" for "%s". The value "%s" is not a valid "ServiceTierType".', __CLASS__, $this->serviceTier));
            }
            $headers['X-Amzn-Bedrock-Service-Tier'] = $this->serviceTier;
        }

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->modelId) {
            throw new InvalidArgument(\sprintf('Missing parameter "modelId" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['modelId'] = $v;
        $uriString = '/model/' . rawurlencode($uri['modelId']) . '/invoke';

        // Prepare Body
        $body = $this->body ?? '';

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAccept(?string $value): self
    {
        $this->accept = $value;

        return $this;
    }

    public function setBody(?string $value): self
    {
        $this->body = $value;

        return $this;
    }

    public function setContentType(?string $value): self
    {
        $this->contentType = $value;

        return $this;
    }

    public function setGuardrailIdentifier(?string $value): self
    {
        $this->guardrailIdentifier = $value;

        return $this;
    }

    public function setGuardrailVersion(?string $value): self
    {
        $this->guardrailVersion = $value;

        return $this;
    }

    public function setModelId(?string $value): self
    {
        $this->modelId = $value;

        return $this;
    }

    /**
     * @param PerformanceConfigLatency::*|null $value
     */
    public function setPerformanceConfigLatency(?string $value): self
    {
        $this->performanceConfigLatency = $value;

        return $this;
    }

    /**
     * @param ServiceTierType::*|null $value
     */
    public function setServiceTier(?string $value): self
    {
        $this->serviceTier = $value;

        return $this;
    }

    /**
     * @param Trace::*|null $value
     */
    public function setTrace(?string $value): self
    {
        $this->trace = $value;

        return $this;
    }
}
