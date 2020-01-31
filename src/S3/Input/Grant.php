<?php

namespace AsyncAws\S3\Input;

class Grant
{
    /**
     * @var Grantee|null
     */
    private $Grantee;

    /**
     * @var string|null
     */
    private $Permission;

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @param array{
     *   Grantee?: \AsyncAws\S3\Input\Grantee|array,
     *   Permission?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Grantee = isset($input["Grantee"]) ? Grantee::create($input["Grantee"]) : null;
        $this->Permission = $input["Permission"] ?? null;
    }

    public function getGrantee(): ?Grantee
    {
        return $this->Grantee;
    }

    public function setGrantee(?Grantee $value): self
    {
        $this->Grantee = $value;

        return $this;
    }

    public function getPermission(): ?string
    {
        return $this->Permission;
    }

    public function setPermission(?string $value): self
    {
        $this->Permission = $value;

        return $this;
    }
}
