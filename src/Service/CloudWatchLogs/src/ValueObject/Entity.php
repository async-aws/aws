<?php

namespace AsyncAws\CloudWatchLogs\ValueObject;

/**
 * The entity associated with the log events in a `PutLogEvents` call.
 */
final class Entity
{
    /**
     * The attributes of the entity which identify the specific entity, as a list of key-value pairs. Entities with the same
     * `keyAttributes` are considered to be the same entity.
     *
     * There are five allowed attributes (key names): `Type`, `ResourceType`, `Identifier``Name`, and `Environment`.
     *
     * For details about how to use the key attributes, see How to add related information to telemetry [^1] in the
     * *CloudWatch User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/adding-your-own-related-telemetry.html
     *
     * @var array<string, string>|null
     */
    private $keyAttributes;

    /**
     * Additional attributes of the entity that are not used to specify the identity of the entity. A list of key-value
     * pairs.
     *
     * For details about how to use the attributes, see How to add related information to telemetry [^1] in the *CloudWatch
     * User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/adding-your-own-related-telemetry.html
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
