<?php

namespace AsyncAws\Iot\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * The input for the ListThings operation.
 */
final class ListThingsRequest extends Input
{
    /**
     * To retrieve the next set of results, the `nextToken` value from a previous response; otherwise **null** to receive
     * the first set of results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The maximum number of results to return in this operation.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * The attribute name used to search for things.
     *
     * @var string|null
     */
    private $attributeName;

    /**
     * The attribute value used to search for things.
     *
     * @var string|null
     */
    private $attributeValue;

    /**
     * The name of the thing type used to search for things.
     *
     * @var string|null
     */
    private $thingTypeName;

    /**
     * When `true`, the action returns the thing resources with attribute values that start with the `attributeValue`
     * provided.
     *
     * When `false`, or not present, the action returns only the thing resources with attribute values that match the entire
     * `attributeValue` provided.
     *
     * @var bool|null
     */
    private $usePrefixAttributeValue;

    /**
     * @param array{
     *   nextToken?: string,
     *   maxResults?: int,
     *   attributeName?: string,
     *   attributeValue?: string,
     *   thingTypeName?: string,
     *   usePrefixAttributeValue?: bool,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->nextToken = $input['nextToken'] ?? null;
        $this->maxResults = $input['maxResults'] ?? null;
        $this->attributeName = $input['attributeName'] ?? null;
        $this->attributeValue = $input['attributeValue'] ?? null;
        $this->thingTypeName = $input['thingTypeName'] ?? null;
        $this->usePrefixAttributeValue = $input['usePrefixAttributeValue'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAttributeName(): ?string
    {
        return $this->attributeName;
    }

    public function getAttributeValue(): ?string
    {
        return $this->attributeValue;
    }

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    public function getThingTypeName(): ?string
    {
        return $this->thingTypeName;
    }

    public function getUsePrefixAttributeValue(): ?bool
    {
        return $this->usePrefixAttributeValue;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/json'];

        // Prepare query
        $query = [];
        if (null !== $this->nextToken) {
            $query['nextToken'] = $this->nextToken;
        }
        if (null !== $this->maxResults) {
            $query['maxResults'] = (string) $this->maxResults;
        }
        if (null !== $this->attributeName) {
            $query['attributeName'] = $this->attributeName;
        }
        if (null !== $this->attributeValue) {
            $query['attributeValue'] = $this->attributeValue;
        }
        if (null !== $this->thingTypeName) {
            $query['thingTypeName'] = $this->thingTypeName;
        }
        if (null !== $this->usePrefixAttributeValue) {
            $query['usePrefixAttributeValue'] = $this->usePrefixAttributeValue ? 'true' : 'false';
        }

        // Prepare URI
        $uriString = '/things';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAttributeName(?string $value): self
    {
        $this->attributeName = $value;

        return $this;
    }

    public function setAttributeValue(?string $value): self
    {
        $this->attributeValue = $value;

        return $this;
    }

    public function setMaxResults(?int $value): self
    {
        $this->maxResults = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    public function setThingTypeName(?string $value): self
    {
        $this->thingTypeName = $value;

        return $this;
    }

    public function setUsePrefixAttributeValue(?bool $value): self
    {
        $this->usePrefixAttributeValue = $value;

        return $this;
    }
}
