<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\RequestSerializer;

/**
 * @internal
 */
class SerializerResultBuilder extends SerializerResult
{
    /**
     * @var string
     */
    private $returnType;

    public function __construct(string $returnType, string $body, array $usedClasses = [], array $extraMethodArgs = [])
    {
        parent::__construct($body, $usedClasses, $extraMethodArgs);
        $this->returnType = $returnType;
    }

    public function getReturnType(): string
    {
        return $this->returnType;
    }
}
