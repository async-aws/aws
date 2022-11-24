<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class ServerSideEncryptionConfiguration
{
    /**
     * Container for information about a particular server-side encryption configuration rule.
     */
    private $rules;

    /**
     * @param array{
     *   Rules: ServerSideEncryptionRule[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->rules = isset($input['Rules']) ? array_map([ServerSideEncryptionRule::class, 'create'], $input['Rules']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ServerSideEncryptionRule[]
     */
    public function getRules(): array
    {
        return $this->rules ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null === $v = $this->rules) {
            throw new InvalidArgument(sprintf('Missing parameter "Rules" for "%s". The value cannot be null.', __CLASS__));
        }
        foreach ($v as $item) {
            $node->appendChild($child = $document->createElement('Rule'));

            $item->requestBody($child, $document);
        }
    }
}
