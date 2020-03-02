<?php

namespace AsyncAws\S3\Result;

use AsyncAws\S3\Enum\Permission;

class Grant
{
    private $Grantee;

    private $Permission;

    /**
     * @param array{
     *   Grantee: null|\AsyncAws\S3\Result\Grantee|array,
     *   Permission: null|\AsyncAws\S3\Enum\Permission::*,
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

    /**
     * The person being granted permissions.
     */
    public function getGrantee(): ?Grantee
    {
        return $this->Grantee;
    }

    /**
     * Specifies the permission given to the grantee.
     *
     * @return Permission::*|null
     */
    public function getPermission(): ?string
    {
        return $this->Permission;
    }
}
