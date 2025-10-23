<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * Provides further details for the reason behind the bad request. For reason type `CODE_ERROR`, the detail will contain
 * a list of code errors.
 */
final class BadRequestDetail
{
    /**
     * Contains the list of errors in the request.
     *
     * @var CodeError[]|null
     */
    private $codeErrors;

    /**
     * @param array{
     *   codeErrors?: array<CodeError|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->codeErrors = isset($input['codeErrors']) ? array_map([CodeError::class, 'create'], $input['codeErrors']) : null;
    }

    /**
     * @param array{
     *   codeErrors?: array<CodeError|array>|null,
     * }|BadRequestDetail $input
     */
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
