<?php

namespace AsyncAws\Rekognition\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Rekognition\Enum\ContentClassifier;

/**
 * Allows you to set attributes of the image. Currently, you can declare an image as free of personally identifiable
 * information.
 */
final class HumanLoopDataAttributes
{
    /**
     * Sets whether the input image is free of personally identifiable information.
     *
     * @var list<ContentClassifier::*>|null
     */
    private $contentClassifiers;

    /**
     * @param array{
     *   ContentClassifiers?: array<ContentClassifier::*>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->contentClassifiers = $input['ContentClassifiers'] ?? null;
    }

    /**
     * @param array{
     *   ContentClassifiers?: array<ContentClassifier::*>|null,
     * }|HumanLoopDataAttributes $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<ContentClassifier::*>
     */
    public function getContentClassifiers(): array
    {
        return $this->contentClassifiers ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->contentClassifiers) {
            $index = -1;
            $payload['ContentClassifiers'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!ContentClassifier::exists($listValue)) {
                    throw new InvalidArgument(\sprintf('Invalid parameter "ContentClassifiers" for "%s". The value "%s" is not a valid "ContentClassifier".', __CLASS__, $listValue));
                }
                $payload['ContentClassifiers'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
