<?php

namespace AsyncAws\Kms\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class CreateAliasRequest extends Input
{
    /**
     * Specifies the alias name. This value must begin with `alias/` followed by a name, such as `alias/ExampleAlias`.
     *
     * @required
     *
     * @var string|null
     */
    private $aliasName;

    /**
     * Associates the alias with the specified customer managed key. The KMS key must be in the same Amazon Web Services
     * Region.
     *
     * @see https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#customer-cmk
     * @required
     *
     * @var string|null
     */
    private $targetKeyId;

    /**
     * @param array{
     *   AliasName?: string,
     *   TargetKeyId?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->aliasName = $input['AliasName'] ?? null;
        $this->targetKeyId = $input['TargetKeyId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAliasName(): ?string
    {
        return $this->aliasName;
    }

    public function getTargetKeyId(): ?string
    {
        return $this->targetKeyId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'TrentService.CreateAlias',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAliasName(?string $value): self
    {
        $this->aliasName = $value;

        return $this;
    }

    public function setTargetKeyId(?string $value): self
    {
        $this->targetKeyId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->aliasName) {
            throw new InvalidArgument(sprintf('Missing parameter "AliasName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['AliasName'] = $v;
        if (null === $v = $this->targetKeyId) {
            throw new InvalidArgument(sprintf('Missing parameter "TargetKeyId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TargetKeyId'] = $v;

        return $payload;
    }
}
