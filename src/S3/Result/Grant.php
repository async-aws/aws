<?php

namespace AsyncAws\S3\Result;

class Grant
{
    private $Grantee;

    private $Permission;

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @param array{
     *   Grantee?: \AsyncAws\S3\Result\Grantee|array,
     *   Permission?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Grantee = isset($input['Grantee']) ? Grantee::create($input['Grantee']) : null;
        $this->Permission = $input['Permission'] ?? null;
    }

    public function getGrantee(): ?Grantee
    {
        return $this->Grantee;
    }

    public function getPermission(): ?string
    {
        return $this->Permission;
    }
}
