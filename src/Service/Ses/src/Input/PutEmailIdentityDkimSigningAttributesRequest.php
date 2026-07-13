<?php

namespace AsyncAws\Ses\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Ses\Enum\DkimSigningAttributesOrigin;
use AsyncAws\Ses\ValueObject\DkimSigningAttributes;

/**
 * A request to change the DKIM attributes for an email identity.
 */
final class PutEmailIdentityDkimSigningAttributesRequest extends Input
{
    /**
     * The email identity.
     *
     * @required
     *
     * @var string|null
     */
    private $emailIdentity;

    /**
     * The method to use to configure DKIM for the identity. There are the following possible values:
     *
     * - `AWS_SES` – Configure DKIM for the identity by using Easy DKIM [^1].
     * - `EXTERNAL` – Configure DKIM for the identity by using Bring Your Own DKIM (BYODKIM).
     *
     * [^1]: https://docs.aws.amazon.com/ses/latest/DeveloperGuide/easy-dkim.html
     *
     * @required
     *
     * @var DkimSigningAttributesOrigin::*|null
     */
    private $signingAttributesOrigin;

    /**
     * An object that contains information about the private key and selector that you want to use to configure DKIM for the
     * identity for Bring Your Own DKIM (BYODKIM) for the identity, or, configures the key length to be used for Easy DKIM
     * [^1].
     *
     * [^1]: https://docs.aws.amazon.com/ses/latest/DeveloperGuide/easy-dkim.html
     *
     * @var DkimSigningAttributes|null
     */
    private $signingAttributes;

    /**
     * @param array{
     *   EmailIdentity?: string,
     *   SigningAttributesOrigin?: DkimSigningAttributesOrigin::*,
     *   SigningAttributes?: DkimSigningAttributes|array|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->emailIdentity = $input['EmailIdentity'] ?? null;
        $this->signingAttributesOrigin = $input['SigningAttributesOrigin'] ?? null;
        $this->signingAttributes = isset($input['SigningAttributes']) ? DkimSigningAttributes::create($input['SigningAttributes']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   EmailIdentity?: string,
     *   SigningAttributesOrigin?: DkimSigningAttributesOrigin::*,
     *   SigningAttributes?: DkimSigningAttributes|array|null,
     *   '@region'?: string|null,
     * }|PutEmailIdentityDkimSigningAttributesRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEmailIdentity(): ?string
    {
        return $this->emailIdentity;
    }

    public function getSigningAttributes(): ?DkimSigningAttributes
    {
        return $this->signingAttributes;
    }

    /**
     * @return DkimSigningAttributesOrigin::*|null
     */
    public function getSigningAttributesOrigin(): ?string
    {
        return $this->signingAttributesOrigin;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->emailIdentity) {
            throw new InvalidArgument(\sprintf('Missing parameter "EmailIdentity" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['EmailIdentity'] = $v;
        $uriString = '/v2/email/identities/' . rawurlencode($uri['EmailIdentity']) . '/dkim/signing';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('PUT', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setEmailIdentity(?string $value): self
    {
        $this->emailIdentity = $value;

        return $this;
    }

    public function setSigningAttributes(?DkimSigningAttributes $value): self
    {
        $this->signingAttributes = $value;

        return $this;
    }

    /**
     * @param DkimSigningAttributesOrigin::*|null $value
     */
    public function setSigningAttributesOrigin(?string $value): self
    {
        $this->signingAttributesOrigin = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null === $v = $this->signingAttributesOrigin) {
            throw new InvalidArgument(\sprintf('Missing parameter "SigningAttributesOrigin" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!DkimSigningAttributesOrigin::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "SigningAttributesOrigin" for "%s". The value "%s" is not a valid "DkimSigningAttributesOrigin".', __CLASS__, $v));
        }
        $payload['SigningAttributesOrigin'] = $v;
        if (null !== $v = $this->signingAttributes) {
            $payload['SigningAttributes'] = $v->requestBody();
        }

        return $payload;
    }
}
