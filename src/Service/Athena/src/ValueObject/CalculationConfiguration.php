<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Contains configuration information for the calculation.
 */
final class CalculationConfiguration
{
    /**
     * A string that contains the code for the calculation.
     */
    private $codeBlock;

    /**
     * @param array{
     *   CodeBlock?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->codeBlock = $input['CodeBlock'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCodeBlock(): ?string
    {
        return $this->codeBlock;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->codeBlock) {
            $payload['CodeBlock'] = $v;
        }

        return $payload;
    }
}
