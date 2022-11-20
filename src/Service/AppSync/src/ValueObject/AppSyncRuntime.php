<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\AppSync\Enum\RuntimeName;
use AsyncAws\Core\Exception\InvalidArgument;

final class AppSyncRuntime
{
    /**
     * The `name` of the runtime to use. Currently, the only allowed value is `APPSYNC_JS`.
     */
    private $name;

    /**
     * The `version` of the runtime to use. Currently, the only allowed version is `1.0.0`.
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
        $this->name = $input['name'] ?? null;
        $this->runtimeVersion = $input['runtimeVersion'] ?? null;
    }

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
        if (null === $v = $this->name) {
            throw new InvalidArgument(sprintf('Missing parameter "name" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!RuntimeName::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "name" for "%s". The value "%s" is not a valid "RuntimeName".', __CLASS__, $v));
        }
        $payload['name'] = $v;
        if (null === $v = $this->runtimeVersion) {
            throw new InvalidArgument(sprintf('Missing parameter "runtimeVersion" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['runtimeVersion'] = $v;

        return $payload;
    }
}
