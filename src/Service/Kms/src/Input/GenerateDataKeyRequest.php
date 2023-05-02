<?php

namespace AsyncAws\Kms\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Kms\Enum\DataKeySpec;
use AsyncAws\Kms\ValueObject\RecipientInfo;

final class GenerateDataKeyRequest extends Input
{
    /**
     * Specifies the symmetric encryption KMS key that encrypts the data key. You cannot specify an asymmetric KMS key or a
     * KMS key in a custom key store. To get the type and origin of your KMS key, use the DescribeKey operation.
     *
     * @required
     *
     * @var string|null
     */
    private $keyId;

    /**
     * Specifies the encryption context that will be used when encrypting the data key.
     *
     * @var array<string, string>|null
     */
    private $encryptionContext;

    /**
     * Specifies the length of the data key in bytes. For example, use the value 64 to generate a 512-bit data key (64 bytes
     * is 512 bits). For 128-bit (16-byte) and 256-bit (32-byte) data keys, use the `KeySpec` parameter.
     *
     * @var int|null
     */
    private $numberOfBytes;

    /**
     * Specifies the length of the data key. Use `AES_128` to generate a 128-bit symmetric key, or `AES_256` to generate a
     * 256-bit symmetric key.
     *
     * @var DataKeySpec::*|null
     */
    private $keySpec;

    /**
     * A list of grant tokens.
     *
     * @var string[]|null
     */
    private $grantTokens;

    /**
     * A signed attestation document from an Amazon Web Services Nitro enclave and the encryption algorithm to use with the
     * enclave's public key. The only valid encryption algorithm is `RSAES_OAEP_SHA_256`.
     *
     * @see https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/nitro-enclave-how.html#term-attestdoc
     *
     * @var RecipientInfo|null
     */
    private $recipient;

    /**
     * @param array{
     *   KeyId?: string,
     *   EncryptionContext?: array<string, string>,
     *   NumberOfBytes?: int,
     *   KeySpec?: DataKeySpec::*,
     *   GrantTokens?: string[],
     *   Recipient?: RecipientInfo|array,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->keyId = $input['KeyId'] ?? null;
        $this->encryptionContext = $input['EncryptionContext'] ?? null;
        $this->numberOfBytes = $input['NumberOfBytes'] ?? null;
        $this->keySpec = $input['KeySpec'] ?? null;
        $this->grantTokens = $input['GrantTokens'] ?? null;
        $this->recipient = isset($input['Recipient']) ? RecipientInfo::create($input['Recipient']) : null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, string>
     */
    public function getEncryptionContext(): array
    {
        return $this->encryptionContext ?? [];
    }

    /**
     * @return string[]
     */
    public function getGrantTokens(): array
    {
        return $this->grantTokens ?? [];
    }

    public function getKeyId(): ?string
    {
        return $this->keyId;
    }

    /**
     * @return DataKeySpec::*|null
     */
    public function getKeySpec(): ?string
    {
        return $this->keySpec;
    }

    public function getNumberOfBytes(): ?int
    {
        return $this->numberOfBytes;
    }

    public function getRecipient(): ?RecipientInfo
    {
        return $this->recipient;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'TrentService.GenerateDataKey',
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

    /**
     * @param array<string, string> $value
     */
    public function setEncryptionContext(array $value): self
    {
        $this->encryptionContext = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setGrantTokens(array $value): self
    {
        $this->grantTokens = $value;

        return $this;
    }

    public function setKeyId(?string $value): self
    {
        $this->keyId = $value;

        return $this;
    }

    /**
     * @param DataKeySpec::*|null $value
     */
    public function setKeySpec(?string $value): self
    {
        $this->keySpec = $value;

        return $this;
    }

    public function setNumberOfBytes(?int $value): self
    {
        $this->numberOfBytes = $value;

        return $this;
    }

    public function setRecipient(?RecipientInfo $value): self
    {
        $this->recipient = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->keyId) {
            throw new InvalidArgument(sprintf('Missing parameter "KeyId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['KeyId'] = $v;
        if (null !== $v = $this->encryptionContext) {
            if (empty($v)) {
                $payload['EncryptionContext'] = new \stdClass();
            } else {
                $payload['EncryptionContext'] = [];
                foreach ($v as $name => $mv) {
                    $payload['EncryptionContext'][$name] = $mv;
                }
            }
        }
        if (null !== $v = $this->numberOfBytes) {
            $payload['NumberOfBytes'] = $v;
        }
        if (null !== $v = $this->keySpec) {
            if (!DataKeySpec::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "KeySpec" for "%s". The value "%s" is not a valid "DataKeySpec".', __CLASS__, $v));
            }
            $payload['KeySpec'] = $v;
        }
        if (null !== $v = $this->grantTokens) {
            $index = -1;
            $payload['GrantTokens'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['GrantTokens'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->recipient) {
            $payload['Recipient'] = $v->requestBody();
        }

        return $payload;
    }
}
