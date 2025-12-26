<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\RequestSerializer;

/**
 * @internal
 */
class SerializerResultBody extends SerializerResult
{
    /**
     * @var bool
     */
    private $requestBody;

    /**
     * @var array<string, string>
     */
    private $extraMethodArgs;

    public function __construct(string $body, bool $requestBody, array $usedClasses = [], array $extraMethodArgs = [])
    {
        parent::__construct($body, $usedClasses);
        $this->requestBody = $requestBody;
        $this->extraMethodArgs = $extraMethodArgs;
    }

    public function hasRequestBody(): bool
    {
        return $this->requestBody;
    }

    /**
     * @return array<string, string>
     */
    public function getExtraMethodArgs(): array
    {
        return $this->extraMethodArgs;
    }
}
