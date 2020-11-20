<?php

namespace AsyncAws\S3\ValueObject;

final class S3KeyFilter
{
    private $FilterRules;

    /**
     * @param array{
     *   FilterRules?: null|FilterRule[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->FilterRules = isset($input['FilterRules']) ? array_map([FilterRule::class, 'create'], $input['FilterRules']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return FilterRule[]
     */
    public function getFilterRules(): array
    {
        return $this->FilterRules ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null !== $v = $this->FilterRules) {
            foreach ($v as $item) {
                $node->appendChild($child = $document->createElement('FilterRule'));

                $item->requestBody($child, $document);
            }
        }
    }
}
