<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\StreamViewType;

/**
 * The settings for DynamoDB Streams on the table. These settings consist of:.
 *
 * - `StreamEnabled` - Indicates whether DynamoDB Streams is to be enabled (true) or disabled (false).
 * - `StreamViewType` - When an item in the table is modified, `StreamViewType` determines what information is written
 *   to the table's stream. Valid values for `StreamViewType` are:
 *
 *   - `KEYS_ONLY` - Only the key attributes of the modified item are written to the stream.
 *   - `NEW_IMAGE` - The entire item, as it appears after it was modified, is written to the stream.
 *   - `OLD_IMAGE` - The entire item, as it appeared before it was modified, is written to the stream.
 *   - `NEW_AND_OLD_IMAGES` - Both the new and the old item images of the item are written to the stream.
 */
final class StreamSpecification
{
    /**
     * Indicates whether DynamoDB Streams is enabled (true) or disabled (false) on the table.
     */
    private $streamEnabled;

    /**
     * When an item in the table is modified, `StreamViewType` determines what information is written to the stream for this
     * table. Valid values for `StreamViewType` are:.
     */
    private $streamViewType;

    /**
     * @param array{
     *   StreamEnabled: bool,
     *   StreamViewType?: null|StreamViewType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->streamEnabled = $input['StreamEnabled'] ?? null;
        $this->streamViewType = $input['StreamViewType'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getStreamEnabled(): bool
    {
        return $this->streamEnabled;
    }

    /**
     * @return StreamViewType::*|null
     */
    public function getStreamViewType(): ?string
    {
        return $this->streamViewType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->streamEnabled) {
            throw new InvalidArgument(sprintf('Missing parameter "StreamEnabled" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['StreamEnabled'] = (bool) $v;
        if (null !== $v = $this->streamViewType) {
            if (!StreamViewType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "StreamViewType" for "%s". The value "%s" is not a valid "StreamViewType".', __CLASS__, $v));
            }
            $payload['StreamViewType'] = $v;
        }

        return $payload;
    }
}
