<?php

namespace AsyncAws\S3\Result;

class Grant
{
    /**
     * The person being granted permissions.
     */
    private $Grantee;

    /**
     * Specifies the permission given to the grantee.
     */
    private $Permission;

    /**
     * @param array{
     *   Grantee: null|\AsyncAws\S3\Result\Grantee|array,
     *   Permission: ?string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Grantee = isset($input['Grantee']) ? Grantee::create($input['Grantee']) : null;
        $this->Permission = $input['Permission'];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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
