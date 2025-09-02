<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\BedrockRuntime\Enum\GuardrailTrace;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Configuration information for a guardrail that you use with the Converse [^1] operation.
 *
 * [^1]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_Converse.html
 */
final class GuardrailConfiguration
{
    /**
     * The identifier for the guardrail.
     *
     * @var string
     */
    private $guardrailIdentifier;

    /**
     * The version of the guardrail.
     *
     * @var string
     */
    private $guardrailVersion;

    /**
     * The trace behavior for the guardrail.
     *
     * @var GuardrailTrace::*|null
     */
    private $trace;

    /**
     * @param array{
     *   guardrailIdentifier: string,
     *   guardrailVersion: string,
     *   trace?: null|GuardrailTrace::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->guardrailIdentifier = $input['guardrailIdentifier'] ?? $this->throwException(new InvalidArgument('Missing required field "guardrailIdentifier".'));
        $this->guardrailVersion = $input['guardrailVersion'] ?? $this->throwException(new InvalidArgument('Missing required field "guardrailVersion".'));
        $this->trace = $input['trace'] ?? null;
    }

    /**
     * @param array{
     *   guardrailIdentifier: string,
     *   guardrailVersion: string,
     *   trace?: null|GuardrailTrace::*,
     * }|GuardrailConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getGuardrailIdentifier(): string
    {
        return $this->guardrailIdentifier;
    }

    public function getGuardrailVersion(): string
    {
        return $this->guardrailVersion;
    }

    /**
     * @return GuardrailTrace::*|null
     */
    public function getTrace(): ?string
    {
        return $this->trace;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->guardrailIdentifier;
        $payload['guardrailIdentifier'] = $v;
        $v = $this->guardrailVersion;
        $payload['guardrailVersion'] = $v;
        if (null !== $v = $this->trace) {
            if (!GuardrailTrace::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "trace" for "%s". The value "%s" is not a valid "GuardrailTrace".', __CLASS__, $v));
            }
            $payload['trace'] = $v;
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
