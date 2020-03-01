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
     * @var Permission::FULL_CONTROL|Permission::WRITE|Permission::WRITE_ACP|Permission::READ|Permission::READ_ACP|null
     */
    private $Permission;

    /**
     * @param array{
     *   Grantee?: \AsyncAws\S3\Input\Grantee|array,
     *   Permission?: \AsyncAws\S3\Enum\Permission::FULL_CONTROL|\AsyncAws\S3\Enum\Permission::WRITE|\AsyncAws\S3\Enum\Permission::WRITE_ACP|\AsyncAws\S3\Enum\Permission::READ|\AsyncAws\S3\Enum\Permission::READ_ACP,
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
     * @return Permission::FULL_CONTROL|Permission::WRITE|Permission::WRITE_ACP|Permission::READ|Permission::READ_ACP|null
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
     * @param Permission::FULL_CONTROL|Permission::WRITE|Permission::WRITE_ACP|Permission::READ|Permission::READ_ACP|null $value
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
            if (!isset(Permission::AVAILABLE_PERMISSION[$this->Permission])) {
                throw new InvalidArgument(sprintf('Invalid parameter "Permission" when validating the "%s". The value "%s" is not a valid "Permission". Available values are %s.', __CLASS__, $this->Permission, implode(', ', array_keys(Permission::AVAILABLE_PERMISSION))));
            }
        }
    }
}
