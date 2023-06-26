<?php

namespace AsyncAws\S3\ValueObject;

/**
 * A container for object key name prefix and suffix filtering rules.
 */
final class S3KeyFilter
{
    /**
     * @var FilterRule[]|null
     */
    private $filterRules;

    /**
     * @param array{
     *   FilterRules?: null|array<FilterRule|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->filterRules = isset($input['FilterRules']) ? array_map([FilterRule::class, 'create'], $input['FilterRules']) : null;
    }

    /**
     * @param array{
     *   FilterRules?: null|array<FilterRule|array>,
     * }|S3KeyFilter $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return FilterRule[]
     */
    public function getFilterRules(): array
    {
        return $this->filterRules ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->filterRules) {
            foreach ($v as $item) {
                $node->appendChild($child = $document->createElement('FilterRule'));

                $item->requestBody($child, $document);
            }
        }
    }
}
