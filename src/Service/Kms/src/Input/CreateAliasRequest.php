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
     * ! Do not include confidential or sensitive information in this field. This field may be displayed in plaintext in
     * ! CloudTrail logs and other output.
     *
     * The `AliasName` value must be string of 1-256 characters. It can contain only alphanumeric characters, forward
     * slashes (/), underscores (_), and dashes (-). The alias name cannot begin with `alias/aws/`. The `alias/aws/` prefix
     * is reserved for Amazon Web Services managed keys [^1].
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#aws-managed-key
     *
     * @required
     *
     * @var string|null
     */
    private $aliasName;

    /**
     * Associates the alias with the specified customer managed key [^1]. The KMS key must be in the same Amazon Web
     * Services Region.
     *
     * A valid key ID is required. If you supply a null or empty string value, this operation returns an error.
     *
     * For help finding the key ID and ARN, see Find the key ID and key ARN [^2] in the **Key Management Service Developer
     * Guide**.
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
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#customer-mgn-key
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/find-cmk-id-arn.html
     *
     * @required
     *
     * @var string|null
     */
    private $targetKeyId;

    /**
     * @param array{
     *   AliasName?: string,
     *   TargetKeyId?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->aliasName = $input['AliasName'] ?? null;
        $this->targetKeyId = $input['TargetKeyId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   AliasName?: string,
     *   TargetKeyId?: string,
     *   '@region'?: string|null,
     * }|CreateAliasRequest $input
     */
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
            throw new InvalidArgument(\sprintf('Missing parameter "AliasName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['AliasName'] = $v;
        if (null === $v = $this->targetKeyId) {
            throw new InvalidArgument(\sprintf('Missing parameter "TargetKeyId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TargetKeyId'] = $v;

        return $payload;
    }
}
