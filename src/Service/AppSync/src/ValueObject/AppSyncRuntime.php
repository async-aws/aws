<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\AppSync\Enum\RuntimeName;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Describes a runtime used by an Amazon Web Services AppSync pipeline resolver or Amazon Web Services AppSync function.
 * Specifies the name and version of the runtime to use. Note that if a runtime is specified, code must also be
 * specified.
 */
final class AppSyncRuntime
{
    /**
     * The `name` of the runtime to use. Currently, the only allowed value is `APPSYNC_JS`.
     *
     * @var RuntimeName::*
     */
    private $name;

    /**
     * The `version` of the runtime to use. Currently, the only allowed version is `1.0.0`.
     *
     * @var string
     */
    private $runtimeVersion;

    /**
     * @param array{
     *   name: RuntimeName::*,
     *   runtimeVersion: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? $this->throwException(new InvalidArgument('Missing required field "name".'));
        $this->runtimeVersion = $input['runtimeVersion'] ?? $this->throwException(new InvalidArgument('Missing required field "runtimeVersion".'));
    }

    /**
     * @param array{
     *   name: RuntimeName::*,
     *   runtimeVersion: string,
     * }|AppSyncRuntime $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return RuntimeName::*
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getRuntimeVersion(): string
    {
        return $this->runtimeVersion;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->name;
        if (!RuntimeName::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "name" for "%s". The value "%s" is not a valid "RuntimeName".', __CLASS__, $v));
        }
        $payload['name'] = $v;
        $v = $this->runtimeVersion;
        $payload['runtimeVersion'] = $v;

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
