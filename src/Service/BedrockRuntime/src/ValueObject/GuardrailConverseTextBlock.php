<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\BedrockRuntime\Enum\GuardrailConverseContentQualifier;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A text block that contains text that you want to assess with a guardrail. For more information, see
 * GuardrailConverseContentBlock.
 */
final class GuardrailConverseTextBlock
{
    /**
     * The text that you want to guard.
     *
     * @var string
     */
    private $text;

    /**
     * The qualifier details for the guardrails contextual grounding filter.
     *
     * @var list<GuardrailConverseContentQualifier::*>|null
     */
    private $qualifiers;

    /**
     * @param array{
     *   text: string,
     *   qualifiers?: null|array<GuardrailConverseContentQualifier::*>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->text = $input['text'] ?? $this->throwException(new InvalidArgument('Missing required field "text".'));
        $this->qualifiers = $input['qualifiers'] ?? null;
    }

    /**
     * @param array{
     *   text: string,
     *   qualifiers?: null|array<GuardrailConverseContentQualifier::*>,
     * }|GuardrailConverseTextBlock $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<GuardrailConverseContentQualifier::*>
     */
    public function getQualifiers(): array
    {
        return $this->qualifiers ?? [];
    }

    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->text;
        $payload['text'] = $v;
        if (null !== $v = $this->qualifiers) {
            $index = -1;
            $payload['qualifiers'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!GuardrailConverseContentQualifier::exists($listValue)) {
                    throw new InvalidArgument(\sprintf('Invalid parameter "qualifiers" for "%s". The value "%s" is not a valid "GuardrailConverseContentQualifier".', __CLASS__, $listValue));
                }
                $payload['qualifiers'][$index] = $listValue;
            }
        }

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
