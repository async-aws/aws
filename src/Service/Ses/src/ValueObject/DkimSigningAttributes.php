<?php

namespace AsyncAws\Ses\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Ses\Enum\DkimSigningAttributesOrigin;
use AsyncAws\Ses\Enum\DkimSigningKeyLength;

/**
 * An object that contains configuration for Bring Your Own DKIM (BYODKIM), or, for Easy DKIM.
 */
final class DkimSigningAttributes
{
    /**
     * [Bring Your Own DKIM] A string that's used to identify a public key in the DNS configuration for a domain.
     *
     * @var string|null
     */
    private $domainSigningSelector;

    /**
     * [Bring Your Own DKIM] A private key that's used to generate a DKIM signature.
     *
     * The private key must use 1024 or 2048-bit RSA encryption, and must be encoded using base64 encoding.
     *
     * @var string|null
     */
    private $domainSigningPrivateKey;

    /**
     * [Easy DKIM] The key length of the future DKIM key pair to be generated. This can be changed at most once per day.
     *
     * @var DkimSigningKeyLength::*|null
     */
    private $nextSigningKeyLength;

    /**
     * The attribute to use for configuring DKIM for the identity depends on the operation:
     *
     * 1. For `PutEmailIdentityDkimSigningAttributes`:
     *
     *    - None of the values are allowed - use the `SigningAttributesOrigin` [^1] parameter instead
     *
     * 2. For `CreateEmailIdentity` when replicating a parent identity's DKIM configuration:
     *
     *    - Allowed values: All values except `AWS_SES` and `EXTERNAL`
     *
     *
     * - `AWS_SES` – Configure DKIM for the identity by using Easy DKIM.
     * - `EXTERNAL` – Configure DKIM for the identity by using Bring Your Own DKIM (BYODKIM).
     * - `AWS_SES_AF_SOUTH_1` – Configure DKIM for the identity by replicating from a parent identity in Africa (Cape
     *   Town) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_EU_NORTH_1` – Configure DKIM for the identity by replicating from a parent identity in Europe
     *   (Stockholm) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_AP_SOUTH_1` – Configure DKIM for the identity by replicating from a parent identity in Asia Pacific
     *   (Mumbai) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_AP_SOUTH_2` – Configure DKIM for the identity by replicating from a parent identity in Asia Pacific
     *   (Hyderabad) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_EU_WEST_3` – Configure DKIM for the identity by replicating from a parent identity in Europe (Paris)
     *   region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_EU_WEST_2` – Configure DKIM for the identity by replicating from a parent identity in Europe (London)
     *   region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_EU_SOUTH_1` – Configure DKIM for the identity by replicating from a parent identity in Europe (Milan)
     *   region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_EU_WEST_1` – Configure DKIM for the identity by replicating from a parent identity in Europe (Ireland)
     *   region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_AP_NORTHEAST_3` – Configure DKIM for the identity by replicating from a parent identity in Asia Pacific
     *   (Osaka) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_AP_NORTHEAST_2` – Configure DKIM for the identity by replicating from a parent identity in Asia Pacific
     *   (Seoul) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_ME_CENTRAL_1` – Configure DKIM for the identity by replicating from a parent identity in Middle East
     *   (UAE) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_ME_SOUTH_1` – Configure DKIM for the identity by replicating from a parent identity in Middle East
     *   (Bahrain) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_AP_NORTHEAST_1` – Configure DKIM for the identity by replicating from a parent identity in Asia Pacific
     *   (Tokyo) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_IL_CENTRAL_1` – Configure DKIM for the identity by replicating from a parent identity in Israel (Tel
     *   Aviv) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_SA_EAST_1` – Configure DKIM for the identity by replicating from a parent identity in South America
     *   (São Paulo) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_CA_CENTRAL_1` – Configure DKIM for the identity by replicating from a parent identity in Canada
     *   (Central) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_CA_WEST_1` – Configure DKIM for the identity by replicating from a parent identity in Canada (Calgary)
     *   region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_AP_SOUTHEAST_1` – Configure DKIM for the identity by replicating from a parent identity in Asia Pacific
     *   (Singapore) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_AP_SOUTHEAST_2` – Configure DKIM for the identity by replicating from a parent identity in Asia Pacific
     *   (Sydney) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_AP_SOUTHEAST_3` – Configure DKIM for the identity by replicating from a parent identity in Asia Pacific
     *   (Jakarta) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_AP_SOUTHEAST_5` – Configure DKIM for the identity by replicating from a parent identity in Asia Pacific
     *   (Malaysia) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_EU_CENTRAL_1` – Configure DKIM for the identity by replicating from a parent identity in Europe
     *   (Frankfurt) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_EU_CENTRAL_2` – Configure DKIM for the identity by replicating from a parent identity in Europe (Zurich)
     *   region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_US_EAST_1` – Configure DKIM for the identity by replicating from a parent identity in US East (N.
     *   Virginia) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_US_EAST_2` – Configure DKIM for the identity by replicating from a parent identity in US East (Ohio)
     *   region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_US_WEST_1` – Configure DKIM for the identity by replicating from a parent identity in US West (N.
     *   California) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_US_WEST_2` – Configure DKIM for the identity by replicating from a parent identity in US West (Oregon)
     *   region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_US_GOV_EAST_1` – Configure DKIM for the identity by replicating from a parent identity in AWS GovCloud
     *   (US-East) region using Deterministic Easy-DKIM (DEED).
     * - `AWS_SES_US_GOV_WEST_1` – Configure DKIM for the identity by replicating from a parent identity in AWS GovCloud
     *   (US-West) region using Deterministic Easy-DKIM (DEED).
     *
     * [^1]: https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_PutEmailIdentityDkimSigningAttributes.html#SES-PutEmailIdentityDkimSigningAttributes-request-SigningAttributesOrigin
     *
     * @var DkimSigningAttributesOrigin::*|null
     */
    private $domainSigningAttributesOrigin;

    /**
     * @param array{
     *   DomainSigningSelector?: string|null,
     *   DomainSigningPrivateKey?: string|null,
     *   NextSigningKeyLength?: DkimSigningKeyLength::*|null,
     *   DomainSigningAttributesOrigin?: DkimSigningAttributesOrigin::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->domainSigningSelector = $input['DomainSigningSelector'] ?? null;
        $this->domainSigningPrivateKey = $input['DomainSigningPrivateKey'] ?? null;
        $this->nextSigningKeyLength = $input['NextSigningKeyLength'] ?? null;
        $this->domainSigningAttributesOrigin = $input['DomainSigningAttributesOrigin'] ?? null;
    }

    /**
     * @param array{
     *   DomainSigningSelector?: string|null,
     *   DomainSigningPrivateKey?: string|null,
     *   NextSigningKeyLength?: DkimSigningKeyLength::*|null,
     *   DomainSigningAttributesOrigin?: DkimSigningAttributesOrigin::*|null,
     * }|DkimSigningAttributes $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DkimSigningAttributesOrigin::*|null
     */
    public function getDomainSigningAttributesOrigin(): ?string
    {
        return $this->domainSigningAttributesOrigin;
    }

    public function getDomainSigningPrivateKey(): ?string
    {
        return $this->domainSigningPrivateKey;
    }

    public function getDomainSigningSelector(): ?string
    {
        return $this->domainSigningSelector;
    }

    /**
     * @return DkimSigningKeyLength::*|null
     */
    public function getNextSigningKeyLength(): ?string
    {
        return $this->nextSigningKeyLength;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->domainSigningSelector) {
            $payload['DomainSigningSelector'] = $v;
        }
        if (null !== $v = $this->domainSigningPrivateKey) {
            $payload['DomainSigningPrivateKey'] = $v;
        }
        if (null !== $v = $this->nextSigningKeyLength) {
            if (!DkimSigningKeyLength::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "NextSigningKeyLength" for "%s". The value "%s" is not a valid "DkimSigningKeyLength".', __CLASS__, $v));
            }
            $payload['NextSigningKeyLength'] = $v;
        }
        if (null !== $v = $this->domainSigningAttributesOrigin) {
            if (!DkimSigningAttributesOrigin::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "DomainSigningAttributesOrigin" for "%s". The value "%s" is not a valid "DkimSigningAttributesOrigin".', __CLASS__, $v));
            }
            $payload['DomainSigningAttributesOrigin'] = $v;
        }

        return $payload;
    }
}
