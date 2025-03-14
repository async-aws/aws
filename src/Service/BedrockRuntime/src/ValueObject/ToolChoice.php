<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * Determines which tools the model should request in a call to `Converse` or `ConverseStream`. `ToolChoice` is only
 * supported by Anthropic Claude 3 models and by Mistral AI Mistral Large.
 */
final class ToolChoice
{
    /**
     * (Default). The Model automatically decides if a tool should be called or whether to generate text instead.
     *
     * @var AutoToolChoice|null
     */
    private $auto;

    /**
     * The model must request at least one tool (no text is generated).
     *
     * @var AnyToolChoice|null
     */
    private $any;

    /**
     * The Model must request the specified tool. Only supported by Anthropic Claude 3 models.
     *
     * @var SpecificToolChoice|null
     */
    private $tool;

    /**
     * @param array{
     *   auto?: null|AutoToolChoice|array,
     *   any?: null|AnyToolChoice|array,
     *   tool?: null|SpecificToolChoice|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->auto = isset($input['auto']) ? AutoToolChoice::create($input['auto']) : null;
        $this->any = isset($input['any']) ? AnyToolChoice::create($input['any']) : null;
        $this->tool = isset($input['tool']) ? SpecificToolChoice::create($input['tool']) : null;
    }

    /**
     * @param array{
     *   auto?: null|AutoToolChoice|array,
     *   any?: null|AnyToolChoice|array,
     *   tool?: null|SpecificToolChoice|array,
     * }|ToolChoice $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAny(): ?AnyToolChoice
    {
        return $this->any;
    }

    public function getAuto(): ?AutoToolChoice
    {
        return $this->auto;
    }

    public function getTool(): ?SpecificToolChoice
    {
        return $this->tool;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->auto) {
            $payload['auto'] = $v->requestBody();
        }
        if (null !== $v = $this->any) {
            $payload['any'] = $v->requestBody();
        }
        if (null !== $v = $this->tool) {
            $payload['tool'] = $v->requestBody();
        }

        return $payload;
    }
}
