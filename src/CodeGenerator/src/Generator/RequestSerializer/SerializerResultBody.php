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

    public function __construct(string $body, bool $requestBody, array $usedClasses = [], array $extraMethodArgs = [])
    {
        parent::__construct($body, $usedClasses, $extraMethodArgs);
        $this->requestBody = $requestBody;
    }

    public function hasRequestBody(): bool
    {
        return $this->requestBody;
    }
}
