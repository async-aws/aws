<?php

namespace AsyncAws\AppSync\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DeleteResolverRequest extends Input
{
    /**
     * The API ID.
     *
     * @required
     *
     * @var string|null
     */
    private $apiId;

    /**
     * The name of the resolver type.
     *
     * @required
     *
     * @var string|null
     */
    private $typeName;

    /**
     * The resolver field name.
     *
     * @required
     *
     * @var string|null
     */
    private $fieldName;

    /**
     * @param array{
     *   apiId?: string,
     *   typeName?: string,
     *   fieldName?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->apiId = $input['apiId'] ?? null;
        $this->typeName = $input['typeName'] ?? null;
        $this->fieldName = $input['fieldName'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getApiId(): ?string
    {
        return $this->apiId;
    }

    public function getFieldName(): ?string
    {
        return $this->fieldName;
    }

    public function getTypeName(): ?string
    {
        return $this->typeName;
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

        // Prepare URI
        $uri = [];
        if (null === $v = $this->apiId) {
            throw new InvalidArgument(sprintf('Missing parameter "apiId" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['apiId'] = $v;
        if (null === $v = $this->typeName) {
            throw new InvalidArgument(sprintf('Missing parameter "typeName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['typeName'] = $v;
        if (null === $v = $this->fieldName) {
            throw new InvalidArgument(sprintf('Missing parameter "fieldName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['fieldName'] = $v;
        $uriString = '/v1/apis/' . rawurlencode($uri['apiId']) . '/types/' . rawurlencode($uri['typeName']) . '/resolvers/' . rawurlencode($uri['fieldName']);

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('DELETE', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setApiId(?string $value): self
    {
        $this->apiId = $value;

        return $this;
    }

    public function setFieldName(?string $value): self
    {
        $this->fieldName = $value;

        return $this;
    }

    public function setTypeName(?string $value): self
    {
        $this->typeName = $value;

        return $this;
    }
}
