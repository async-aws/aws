<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\AttributeAction;

/**
 * For the `UpdateItem` operation, represents the attributes to be modified, the action to perform on each, and the new
 * value for each.
 *
 * > You cannot use `UpdateItem` to update any primary key attributes. Instead, you will need to delete the item, and
 * > then use `PutItem` to create a new item with new attributes.
 *
 * Attribute values cannot be null; string and binary type attributes must have lengths greater than zero; and set type
 * attributes must not be empty. Requests with empty values will be rejected with a `ValidationException` exception.
 */
final class AttributeValueUpdate
{
    /**
     * Represents the data for an attribute.
     */
    private $Value;

    /**
     * Specifies how to perform the update. Valid values are `PUT` (default), `DELETE`, and `ADD`. The behavior depends on
     * whether the specified primary key already exists in the table.
     */
    private $Action;

    /**
     * @param array{
     *   Value?: null|AttributeValue|array,
     *   Action?: null|AttributeAction::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Value = isset($input['Value']) ? AttributeValue::create($input['Value']) : null;
        $this->Action = $input['Action'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AttributeAction::*|null
     */
    public function getAction(): ?string
    {
        return $this->Action;
    }

    public function getValue(): ?AttributeValue
    {
        return $this->Value;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->Value) {
            $payload['Value'] = $v->requestBody();
        }
        if (null !== $v = $this->Action) {
            if (!AttributeAction::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Action" for "%s". The value "%s" is not a valid "AttributeAction".', __CLASS__, $v));
            }
            $payload['Action'] = $v;
        }

        return $payload;
    }
}
