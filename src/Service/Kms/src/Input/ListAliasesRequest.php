<?php

namespace AsyncAws\Kms\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListAliasesRequest extends Input
{
    /**
     * Lists only aliases that are associated with the specified KMS key. Enter a KMS key in your Amazon Web Services
     * account.
     *
     * This parameter is optional. If you omit it, `ListAliases` returns all aliases in the account and Region.
     *
     * Specify the key ID or key ARN of the KMS key.
     *
     * For example:
     *
     * - Key ID: `1234abcd-12ab-34cd-56ef-1234567890ab`
     * - Key ARN: `arn:aws:kms:us-east-2:111122223333:key/1234abcd-12ab-34cd-56ef-1234567890ab`
     *
     * To get the key ID and key ARN for a KMS key, use ListKeys or DescribeKey.
     *
     * @var string|null
     */
    private $keyId;

    /**
     * Use this parameter to specify the maximum number of items to return. When this value is present, KMS does not return
     * more than the specified number of items, but it might return fewer.
     *
     * This value is optional. If you include a value, it must be between 1 and 100, inclusive. If you do not include a
     * value, it defaults to 50.
     *
     * @var int|null
     */
    private $limit;

    /**
     * Use this parameter in a subsequent request after you receive a response with truncated results. Set it to the value
     * of `NextMarker` from the truncated response you just received.
     *
     * @var string|null
     */
    private $marker;

    /**
     * @param array{
     *   KeyId?: string|null,
     *   Limit?: int|null,
     *   Marker?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->keyId = $input['KeyId'] ?? null;
        $this->limit = $input['Limit'] ?? null;
        $this->marker = $input['Marker'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   KeyId?: string|null,
     *   Limit?: int|null,
     *   Marker?: string|null,
     *   '@region'?: string|null,
     * }|ListAliasesRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKeyId(): ?string
    {
        return $this->keyId;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getMarker(): ?string
    {
        return $this->marker;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'TrentService.ListAliases',
            'Accept' => 'application/json',
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

    public function setKeyId(?string $value): self
    {
        $this->keyId = $value;

        return $this;
    }

    public function setLimit(?int $value): self
    {
        $this->limit = $value;

        return $this;
    }

    public function setMarker(?string $value): self
    {
        $this->marker = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->keyId) {
            $payload['KeyId'] = $v;
        }
        if (null !== $v = $this->limit) {
            $payload['Limit'] = $v;
        }
        if (null !== $v = $this->marker) {
            $payload['Marker'] = $v;
        }

        return $payload;
    }
}
