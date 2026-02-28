<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The name and value of a user attribute.
 */
final class AttributeType
{
    /**
     * The name of the attribute, for example `email` or `custom:department`.
     *
     * In some older user pools, the regex pattern for acceptable values of this parameter is
     * `[\p{L}\p{M}\p{S}\p{N}\p{P}]+`. Older pools will eventually be updated to use the new pattern. Affected user pools
     * are those created before May 2024 in US East (N. Virginia), US East (Ohio), US West (N. California), US West
     * (Oregon), Asia Pacific (Mumbai), Asia Pacific (Tokyo), Asia Pacific (Seoul), Asia Pacific (Singapore), Asia Pacific
     * (Sydney), Canada (Central), Europe (Frankfurt), Europe (Ireland), Europe (London), Europe (Paris), Europe
     * (Stockholm), Middle East (Bahrain), and South America (São Paulo).
     *
     * @var string
     */
    private $name;

    /**
     * The value of the attribute.
     *
     * @var string|null
     */
    private $value;

    /**
     * @param array{
     *   Name: string,
     *   Value?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->value = $input['Value'] ?? null;
    }

    /**
     * @param array{
     *   Name: string,
     *   Value?: string|null,
     * }|AttributeType $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->name;
        $payload['Name'] = $v;
        if (null !== $v = $this->value) {
            $payload['Value'] = $v;
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
