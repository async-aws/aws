<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * The `PipelineConfig`.
 */
final class PipelineConfig
{
    /**
     * A list of `Function` objects.
     */
    private $functions;

    /**
     * @param array{
     *   functions?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->functions = $input['functions'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getFunctions(): array
    {
        return $this->functions ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->functions) {
            $index = -1;
            $payload['functions'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['functions'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
