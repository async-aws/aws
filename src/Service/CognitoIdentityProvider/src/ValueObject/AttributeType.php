<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The name and value of a user attribute.
 *
 * This data type is a request parameter of AdminUpdateUserAttributes [^1] and UpdateUserAttributes [^2].
 *
 * [^1]: https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminUpdateUserAttributes.html
 * [^2]: https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_UpdateUserAttributes.html
 */
final class AttributeType
{
    /**
     * The name of the attribute.
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
     *   Value?: null|string,
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
     *   Value?: null|string,
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
