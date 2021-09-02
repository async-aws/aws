<?php

namespace AsyncAws\Route53\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Route53\Enum\ChangeAction;

/**
 * The information for each resource record set that you want to change.
 */
final class Change
{
    /**
     * The action to perform:.
     */
    private $action;

    /**
     * Information about the resource record set to create, delete, or update.
     */
    private $resourceRecordSet;

    /**
     * @param array{
     *   Action: ChangeAction::*,
     *   ResourceRecordSet: ResourceRecordSet|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->action = $input['Action'] ?? null;
        $this->resourceRecordSet = isset($input['ResourceRecordSet']) ? ResourceRecordSet::create($input['ResourceRecordSet']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ChangeAction::*
     */
    public function getAction(): string
    {
        return $this->action;
    }

    public function getResourceRecordSet(): ResourceRecordSet
    {
        return $this->resourceRecordSet;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null === $v = $this->action) {
            throw new InvalidArgument(sprintf('Missing parameter "Action" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!ChangeAction::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "Action" for "%s". The value "%s" is not a valid "ChangeAction".', __CLASS__, $v));
        }
        $node->appendChild($document->createElement('Action', $v));
        if (null === $v = $this->resourceRecordSet) {
            throw new InvalidArgument(sprintf('Missing parameter "ResourceRecordSet" for "%s". The value cannot be null.', __CLASS__));
        }

        $node->appendChild($child = $document->createElement('ResourceRecordSet'));

        $v->requestBody($child, $document);
    }
}
