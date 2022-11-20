<?php

namespace AsyncAws\AppSync\ValueObject;

final class BadRequestDetail
{
    /**
     * Contains the list of errors in the request.
     */
    private $codeErrors;

    /**
     * @param array{
     *   codeErrors?: null|CodeError[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->codeErrors = isset($input['codeErrors']) ? array_map([CodeError::class, 'create'], $input['codeErrors']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return CodeError[]
     */
    public function getCodeErrors(): array
    {
        return $this->codeErrors ?? [];
    }
}
