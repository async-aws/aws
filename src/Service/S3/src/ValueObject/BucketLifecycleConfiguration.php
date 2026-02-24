<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Specifies the lifecycle configuration for objects in an Amazon S3 bucket. For more information, see Object Lifecycle
 * Management [^1] in the *Amazon S3 User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/object-lifecycle-mgmt.html
 */
final class BucketLifecycleConfiguration
{
    /**
     * A lifecycle rule for individual objects in an Amazon S3 bucket.
     *
     * @var LifecycleRule[]
     */
    private $rules;

    /**
     * @param array{
     *   Rules: array<LifecycleRule|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->rules = isset($input['Rules']) ? array_map([LifecycleRule::class, 'create'], $input['Rules']) : $this->throwException(new InvalidArgument('Missing required field "Rules".'));
    }

    /**
     * @param array{
     *   Rules: array<LifecycleRule|array>,
     * }|BucketLifecycleConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return LifecycleRule[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        $v = $this->rules;
        foreach ($v as $item) {
            $node->appendChild($child = $document->createElement('Rule'));

            $item->requestBody($child, $document);
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
