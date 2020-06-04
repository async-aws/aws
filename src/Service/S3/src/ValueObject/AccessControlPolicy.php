<?php

namespace AsyncAws\S3\ValueObject;

final class AccessControlPolicy
{
    /**
     * A list of grants.
     */
    private $Grants;

    /**
     * Container for the bucket owner's display name and ID.
     */
    private $Owner;

    /**
     * @param array{
     *   Grants?: null|\AsyncAws\S3\ValueObject\Grant[],
     *   Owner?: null|\AsyncAws\S3\ValueObject\Owner|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Grants = isset($input['Grants']) ? array_map([Grant::class, 'create'], $input['Grants']) : null;
        $this->Owner = isset($input['Owner']) ? Owner::create($input['Owner']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Grant[]
     */
    public function getGrants(): array
    {
        return $this->Grants ?? [];
    }

    public function getOwner(): ?Owner
    {
        return $this->Owner;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null !== $v = $this->Grants) {
            $node->appendChild($nodeList = $document->createElement('AccessControlList'));
            foreach ($v as $item) {
                $nodeList->appendChild($child = $document->createElement('Grant'));

                $item->requestBody($child, $document);
            }
        }
        if (null !== $v = $this->Owner) {
            $node->appendChild($child = $document->createElement('Owner'));

            $v->requestBody($child, $document);
        }
    }
}
