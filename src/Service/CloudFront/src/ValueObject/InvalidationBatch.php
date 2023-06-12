<?php

namespace AsyncAws\CloudFront\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * An invalidation batch.
 */
final class InvalidationBatch
{
    /**
     * A complex type that contains information about the objects that you want to invalidate. For more information, see
     * Specifying the Objects to Invalidate [^1] in the *Amazon CloudFront Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/Invalidation.html#invalidation-specifying-objects
     */
    private $paths;

    /**
     * A value that you specify to uniquely identify an invalidation request. CloudFront uses the value to prevent you from
     * accidentally resubmitting an identical request. Whenever you create a new invalidation request, you must specify a
     * new value for `CallerReference` and change other values in the request as applicable. One way to ensure that the
     * value of `CallerReference` is unique is to use a `timestamp`, for example, `20120301090000`.
     *
     * If you make a second invalidation request with the same value for `CallerReference`, and if the rest of the request
     * is the same, CloudFront doesn't create a new invalidation request. Instead, CloudFront returns information about the
     * invalidation request that you previously created with the same `CallerReference`.
     *
     * If `CallerReference` is a value you already sent in a previous invalidation batch request but the content of any
     * `Path` is different from the original request, CloudFront returns an `InvalidationBatchAlreadyExists` error.
     */
    private $callerReference;

    /**
     * @param array{
     *   Paths: Paths|array,
     *   CallerReference: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->paths = isset($input['Paths']) ? Paths::create($input['Paths']) : null;
        $this->callerReference = $input['CallerReference'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCallerReference(): string
    {
        return $this->callerReference;
    }

    public function getPaths(): Paths
    {
        return $this->paths;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null === $v = $this->paths) {
            throw new InvalidArgument(sprintf('Missing parameter "Paths" for "%s". The value cannot be null.', __CLASS__));
        }

        $node->appendChild($child = $document->createElement('Paths'));

        $v->requestBody($child, $document);

        if (null === $v = $this->callerReference) {
            throw new InvalidArgument(sprintf('Missing parameter "CallerReference" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('CallerReference', $v));
    }
}
