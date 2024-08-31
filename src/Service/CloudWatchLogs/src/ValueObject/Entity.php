<?php

namespace AsyncAws\CloudWatchLogs\ValueObject;

/**
 * Reserved for future use.
 */
final class Entity
{
    /**
     * Reserved for future use.
     *
     * @var array<string, string>|null
     */
    private $keyAttributes;

    /**
     * Reserved for future use.
     *
     * @var array<string, string>|null
     */
    private $attributes;

    /**
     * @param array{
     *   keyAttributes?: null|array<string, string>,
     *   attributes?: null|array<string, string>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->keyAttributes = $input['keyAttributes'] ?? null;
        $this->attributes = $input['attributes'] ?? null;
    }

    /**
     * @param array{
     *   keyAttributes?: null|array<string, string>,
     *   attributes?: null|array<string, string>,
     * }|Entity $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, string>
     */
    public function getAttributes(): array
    {
        return $this->attributes ?? [];
    }

    /**
     * @return array<string, string>
     */
    public function getKeyAttributes(): array
    {
        return $this->keyAttributes ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->keyAttributes) {
            if (empty($v)) {
                $payload['keyAttributes'] = new \stdClass();
            } else {
                $payload['keyAttributes'] = [];
                foreach ($v as $name => $mv) {
                    $payload['keyAttributes'][$name] = $mv;
                }
            }
        }
        if (null !== $v = $this->attributes) {
            if (empty($v)) {
                $payload['attributes'] = new \stdClass();
            } else {
                $payload['attributes'] = [];
                foreach ($v as $name => $mv) {
                    $payload['attributes'][$name] = $mv;
                }
            }
        }

        return $payload;
    }
}
