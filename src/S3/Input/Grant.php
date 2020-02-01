<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;

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

    public function validate(): void
    {
        foreach ([''] as $name) {
            if (null === $value = $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }

            if (\is_object($value) && method_exists($value, 'validate')) {
                $value->validate();
            }
        }
    }
}
