<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * Base inference parameters to pass to a model in a call to Converse [^1] or ConverseStream [^2]. For more information,
 * see Inference parameters for foundation models [^3].
 *
 * If you need to pass additional parameters that the model supports, use the `additionalModelRequestFields` request
 * field in the call to `Converse` or `ConverseStream`. For more information, see Model parameters [^4].
 *
 * [^1]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_Converse.html
 * [^2]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_ConverseStream.html
 * [^3]: https://docs.aws.amazon.com/bedrock/latest/userguide/model-parameters.html
 * [^4]: https://docs.aws.amazon.com/bedrock/latest/userguide/model-parameters.html
 */
final class InferenceConfiguration
{
    /**
     * The maximum number of tokens to allow in the generated response. The default value is the maximum allowed value for
     * the model that you are using. For more information, see Inference parameters for foundation models [^1].
     *
     * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/model-parameters.html
     *
     * @var int|null
     */
    private $maxTokens;

    /**
     * The likelihood of the model selecting higher-probability options while generating a response. A lower value makes the
     * model more likely to choose higher-probability options, while a higher value makes the model more likely to choose
     * lower-probability options.
     *
     * The default value is the default value for the model that you are using. For more information, see Inference
     * parameters for foundation models [^1].
     *
     * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/model-parameters.html
     *
     * @var float|null
     */
    private $temperature;

    /**
     * The percentage of most-likely candidates that the model considers for the next token. For example, if you choose a
     * value of 0.8 for `topP`, the model selects from the top 80% of the probability distribution of tokens that could be
     * next in the sequence.
     *
     * The default value is the default value for the model that you are using. For more information, see Inference
     * parameters for foundation models [^1].
     *
     * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/model-parameters.html
     *
     * @var float|null
     */
    private $topP;

    /**
     * A list of stop sequences. A stop sequence is a sequence of characters that causes the model to stop generating the
     * response.
     *
     * @var string[]|null
     */
    private $stopSequences;

    /**
     * @param array{
     *   maxTokens?: null|int,
     *   temperature?: null|float,
     *   topP?: null|float,
     *   stopSequences?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->maxTokens = $input['maxTokens'] ?? null;
        $this->temperature = $input['temperature'] ?? null;
        $this->topP = $input['topP'] ?? null;
        $this->stopSequences = $input['stopSequences'] ?? null;
    }

    /**
     * @param array{
     *   maxTokens?: null|int,
     *   temperature?: null|float,
     *   topP?: null|float,
     *   stopSequences?: null|string[],
     * }|InferenceConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaxTokens(): ?int
    {
        return $this->maxTokens;
    }

    /**
     * @return string[]
     */
    public function getStopSequences(): array
    {
        return $this->stopSequences ?? [];
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function getTopP(): ?float
    {
        return $this->topP;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->maxTokens) {
            $payload['maxTokens'] = $v;
        }
        if (null !== $v = $this->temperature) {
            $payload['temperature'] = $v;
        }
        if (null !== $v = $this->topP) {
            $payload['topP'] = $v;
        }
        if (null !== $v = $this->stopSequences) {
            $index = -1;
            $payload['stopSequences'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['stopSequences'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
