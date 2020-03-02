<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\Permission;

class Grant
{
    /**
     * The person being granted permissions.
     *
     * @var Grantee|null
     */
    private $Grantee;

    /**
     * Specifies the permission given to the grantee.
     *
     * @var Permission::*|null
     */
    private $Permission;

    /**
     * @param array{
     *   Grantee?: \AsyncAws\S3\Input\Grantee|array,
     *   Permission?: \AsyncAws\S3\Enum\Permission::*,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Grantee = isset($input['Grantee']) ? Grantee::create($input['Grantee']) : null;
        $this->Permission = $input['Permission'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getGrantee(): ?Grantee
    {
        return $this->Grantee;
    }

    /**
     * @return Permission::*|null
     */
    public function getPermission(): ?string
    {
        return $this->Permission;
    }

    public function setGrantee(?Grantee $value): self
    {
        $this->Grantee = $value;

        return $this;
    }

    /**
     * @param Permission::*|null $value
     */
    public function setPermission(?string $value): self
    {
        $this->Permission = $value;

        return $this;
    }

    public function validate(): void
    {
        if (null !== $this->Grantee) {
            $this->Grantee->validate();
        }

        if (null !== $this->Permission) {
            if (!Permission::exists($this->Permission)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Permission" when validating the "%s". The value "%s" is not a valid "Permission".', __CLASS__, $this->Permission));
            }
        }
    }
}
