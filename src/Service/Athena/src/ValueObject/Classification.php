<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * A classification refers to a set of specific configurations.
 */
final class Classification
{
    /**
     * The name of the configuration classification.
     *
     * @var string|null
     */
    private $name;

    /**
     * A set of properties specified within a configuration classification.
     *
     * @var array<string, string>|null
     */
    private $properties;

    /**
     * @param array{
     *   Name?: string|null,
     *   Properties?: array<string, string>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->properties = $input['Properties'] ?? null;
    }

    /**
     * @param array{
     *   Name?: string|null,
     *   Properties?: array<string, string>|null,
     * }|Classification $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return array<string, string>
     */
    public function getProperties(): array
    {
        return $this->properties ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->name) {
            $payload['Name'] = $v;
        }
        if (null !== $v = $this->properties) {
            if (empty($v)) {
                $payload['Properties'] = new \stdClass();
            } else {
                $payload['Properties'] = [];
                foreach ($v as $name => $mv) {
                    $payload['Properties'][$name] = $mv;
                }
            }
        }

        return $payload;
    }
}
