<?php

namespace AsyncAws\CloudFront\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The batch information for the invalidation.
 */
final class InvalidationBatch
{
    /**
     * A complex type that contains information about the objects that you want to invalidate. For more information, see
     * Specifying the Objects to Invalidate in the *Amazon CloudFront Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/Invalidation.html#invalidation-specifying-objects
     */
    private $Paths;

    /**
     * A value that you specify to uniquely identify an invalidation request. CloudFront uses the value to prevent you from
     * accidentally resubmitting an identical request. Whenever you create a new invalidation request, you must specify a
     * new value for `CallerReference` and change other values in the request as applicable. One way to ensure that the
     * value of `CallerReference` is unique is to use a `timestamp`, for example, `20120301090000`.
     */
    private $CallerReference;

    /**
     * @param array{
     *   Paths: Paths|array,
     *   CallerReference: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Paths = isset($input['Paths']) ? Paths::create($input['Paths']) : null;
        $this->CallerReference = $input['CallerReference'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCallerReference(): string
    {
        return $this->CallerReference;
    }

    public function getPaths(): Paths
    {
        return $this->Paths;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null === $v = $this->Paths) {
            throw new InvalidArgument(sprintf('Missing parameter "Paths" for "%s". The value cannot be null.', __CLASS__));
        }

        $node->appendChild($child = $document->createElement('Paths'));

        $v->requestBody($child, $document);

        if (null === $v = $this->CallerReference) {
            throw new InvalidArgument(sprintf('Missing parameter "CallerReference" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('CallerReference', $v));
    }
}
