<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\Type;

/**
 * Container for the person being granted permissions.
 */
final class Grantee
{
    /**
     * @var string|null
     */
    private $displayName;

    /**
     * @var string|null
     */
    private $emailAddress;

    /**
     * The canonical user ID of the grantee.
     *
     * @var string|null
     */
    private $id;

    /**
     * Type of grantee.
     *
     * @var Type::*
     */
    private $type;

    /**
     * URI of the grantee group.
     *
     * @var string|null
     */
    private $uri;

    /**
     * @param array{
     *   DisplayName?: string|null,
     *   EmailAddress?: string|null,
     *   ID?: string|null,
     *   Type: Type::*,
     *   URI?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->displayName = $input['DisplayName'] ?? null;
        $this->emailAddress = $input['EmailAddress'] ?? null;
        $this->id = $input['ID'] ?? null;
        $this->type = $input['Type'] ?? $this->throwException(new InvalidArgument('Missing required field "Type".'));
        $this->uri = $input['URI'] ?? null;
    }

    /**
     * @param array{
     *   DisplayName?: string|null,
     *   EmailAddress?: string|null,
     *   ID?: string|null,
     *   Type: Type::*,
     *   URI?: string|null,
     * }|Grantee $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Type::*
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->displayName) {
            $node->appendChild($document->createElement('DisplayName', $v));
        }
        if (null !== $v = $this->emailAddress) {
            $node->appendChild($document->createElement('EmailAddress', $v));
        }
        if (null !== $v = $this->id) {
            $node->appendChild($document->createElement('ID', $v));
        }
        $v = $this->type;
        if (!Type::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "xsi:type" for "%s". The value "%s" is not a valid "Type".', __CLASS__, $v));
        }
        $node->setAttribute('xsi:type', $v);
        if (null !== $v = $this->uri) {
            $node->appendChild($document->createElement('URI', $v));
        }
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
