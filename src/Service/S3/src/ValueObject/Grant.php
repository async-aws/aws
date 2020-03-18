<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\Permission;

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
     *   Grantee?: null|\AsyncAws\S3\ValueObject\Grantee|array,
     *   Permission?: null|\AsyncAws\S3\Enum\Permission::*,
     * } $input
     */
    public function __construct(array $input)
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

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null !== $v = $this->Grantee) {
            $node->appendChild($child = $document->createElement('Grantee'));
            $child->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
            $v->requestBody($child, $document);
        }
        if (null !== $v = $this->Permission) {
            if (!Permission::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Permission" for "%s". The value "%s" is not a valid "Permission".', __CLASS__, $v));
            }
            $node->appendChild($document->createElement('Permission', $v));
        }
    }
}
