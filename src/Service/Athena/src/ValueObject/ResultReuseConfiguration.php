<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Specifies the query result reuse behavior for the query.
 */
final class ResultReuseConfiguration
{
    /**
     * Specifies whether previous query results are reused, and if so, their maximum age.
     *
     * @var ResultReuseByAgeConfiguration|null
     */
    private $resultReuseByAgeConfiguration;

    /**
     * @param array{
     *   ResultReuseByAgeConfiguration?: ResultReuseByAgeConfiguration|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->resultReuseByAgeConfiguration = isset($input['ResultReuseByAgeConfiguration']) ? ResultReuseByAgeConfiguration::create($input['ResultReuseByAgeConfiguration']) : null;
    }

    /**
     * @param array{
     *   ResultReuseByAgeConfiguration?: ResultReuseByAgeConfiguration|array|null,
     * }|ResultReuseConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getResultReuseByAgeConfiguration(): ?ResultReuseByAgeConfiguration
    {
        return $this->resultReuseByAgeConfiguration;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->resultReuseByAgeConfiguration) {
            $payload['ResultReuseByAgeConfiguration'] = $v->requestBody();
        }

        return $payload;
    }
}
