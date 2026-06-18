<?php

namespace AsyncAws\BedrockAgent\ValueObject;

use AsyncAws\BedrockAgent\Enum\AccessControlAccess;
use AsyncAws\BedrockAgent\Enum\AccessControlPrincipalType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * An access control entry specifying a principal and their access level.
 */
final class DocumentAccessControlEntry
{
    /**
     * The user identifier.
     *
     * @var string
     */
    private $name;

    /**
     * The type of principal.
     *
     * @var AccessControlPrincipalType::*
     */
    private $type;

    /**
     * Whether to allow or deny access.
     *
     * @var AccessControlAccess::*
     */
    private $access;

    /**
     * @param array{
     *   name: string,
     *   type: AccessControlPrincipalType::*,
     *   access: AccessControlAccess::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? $this->throwException(new InvalidArgument('Missing required field "name".'));
        $this->type = $input['type'] ?? $this->throwException(new InvalidArgument('Missing required field "type".'));
        $this->access = $input['access'] ?? $this->throwException(new InvalidArgument('Missing required field "access".'));
    }

    /**
     * @param array{
     *   name: string,
     *   type: AccessControlPrincipalType::*,
     *   access: AccessControlAccess::*,
     * }|DocumentAccessControlEntry $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AccessControlAccess::*
     */
    public function getAccess(): string
    {
        return $this->access;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return AccessControlPrincipalType::*
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->name;
        $payload['name'] = $v;
        $v = $this->type;
        if (!AccessControlPrincipalType::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "AccessControlPrincipalType".', __CLASS__, $v));
        }
        $payload['type'] = $v;
        $v = $this->access;
        if (!AccessControlAccess::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "access" for "%s". The value "%s" is not a valid "AccessControlAccess".', __CLASS__, $v));
        }
        $payload['access'] = $v;

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
