<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * The pipeline configuration for a resolver of kind `PIPELINE`.
 */
final class PipelineConfig
{
    /**
     * A list of `Function` objects.
     *
     * @var string[]|null
     */
    private $functions;

    /**
     * @param array{
     *   functions?: string[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->functions = $input['functions'] ?? null;
    }

    /**
     * @param array{
     *   functions?: string[]|null,
     * }|PipelineConfig $input
     */
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
