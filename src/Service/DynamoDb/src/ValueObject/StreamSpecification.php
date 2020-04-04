<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\StreamViewType;

final class StreamSpecification
{
    /**
     * Indicates whether DynamoDB Streams is enabled (true) or disabled (false) on the table.
     */
    private $StreamEnabled;

    /**
     * When an item in the table is modified, `StreamViewType` determines what information is written to the stream for this
     * table. Valid values for `StreamViewType` are:.
     */
    private $StreamViewType;

    /**
     * @param array{
     *   StreamEnabled: bool,
     *   StreamViewType?: null|\AsyncAws\DynamoDb\Enum\StreamViewType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->StreamEnabled = $input['StreamEnabled'] ?? null;
        $this->StreamViewType = $input['StreamViewType'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getStreamEnabled(): bool
    {
        return $this->StreamEnabled;
    }

    /**
     * @return StreamViewType::*|null
     */
    public function getStreamViewType(): ?string
    {
        return $this->StreamViewType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->StreamEnabled) {
            throw new InvalidArgument(sprintf('Missing parameter "StreamEnabled" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['StreamEnabled'] = (bool) $v;
        if (null !== $v = $this->StreamViewType) {
            if (!StreamViewType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "StreamViewType" for "%s". The value "%s" is not a valid "StreamViewType".', __CLASS__, $v));
            }
            $payload['StreamViewType'] = $v;
        }

        return $payload;
    }
}
