<?php

namespace AsyncAws\Ses\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Ses\Enum\DkimSigningAttributesOrigin;
use AsyncAws\Ses\Enum\DkimSigningKeyLength;
use AsyncAws\Ses\Enum\DkimStatus;
use AsyncAws\Ses\Enum\IdentityType;
use AsyncAws\Ses\ValueObject\DkimAttributes;

/**
 * If the email identity is a domain, this object contains information about the DKIM verification status for the
 * domain.
 *
 * If the email identity is an email address, this object is empty.
 */
class CreateEmailIdentityResponse extends Result
{
    /**
     * The email identity type. Note: the `MANAGED_DOMAIN` identity type is not supported.
     *
     * @var IdentityType::*|null
     */
    private $identityType;

    /**
     * Specifies whether or not the identity is verified. You can only send email from verified email addresses or domains.
     * For more information about verifying identities, see the Amazon Pinpoint User Guide [^1].
     *
     * [^1]: https://docs.aws.amazon.com/pinpoint/latest/userguide/channels-email-manage-verify.html
     *
     * @var bool|null
     */
    private $verifiedForSendingStatus;

    /**
     * An object that contains information about the DKIM attributes for the identity.
     *
     * @var DkimAttributes|null
     */
    private $dkimAttributes;

    public function getDkimAttributes(): ?DkimAttributes
    {
        $this->initialize();

        return $this->dkimAttributes;
    }

    /**
     * @return IdentityType::*|null
     */
    public function getIdentityType(): ?string
    {
        $this->initialize();

        return $this->identityType;
    }

    public function getVerifiedForSendingStatus(): ?bool
    {
        $this->initialize();

        return $this->verifiedForSendingStatus;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->identityType = isset($data['IdentityType']) ? (!IdentityType::exists((string) $data['IdentityType']) ? IdentityType::UNKNOWN_TO_SDK : (string) $data['IdentityType']) : null;
        $this->verifiedForSendingStatus = isset($data['VerifiedForSendingStatus']) ? filter_var($data['VerifiedForSendingStatus'], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->dkimAttributes = empty($data['DkimAttributes']) ? null : $this->populateResultDkimAttributes($data['DkimAttributes']);
    }

    private function populateResultDkimAttributes(array $json): DkimAttributes
    {
        return new DkimAttributes([
            'SigningEnabled' => isset($json['SigningEnabled']) ? filter_var($json['SigningEnabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'Status' => isset($json['Status']) ? (!DkimStatus::exists((string) $json['Status']) ? DkimStatus::UNKNOWN_TO_SDK : (string) $json['Status']) : null,
            'Tokens' => !isset($json['Tokens']) ? null : $this->populateResultDnsTokenList($json['Tokens']),
            'SigningHostedZone' => isset($json['SigningHostedZone']) ? (string) $json['SigningHostedZone'] : null,
            'SigningAttributesOrigin' => isset($json['SigningAttributesOrigin']) ? (!DkimSigningAttributesOrigin::exists((string) $json['SigningAttributesOrigin']) ? DkimSigningAttributesOrigin::UNKNOWN_TO_SDK : (string) $json['SigningAttributesOrigin']) : null,
            'NextSigningKeyLength' => isset($json['NextSigningKeyLength']) ? (!DkimSigningKeyLength::exists((string) $json['NextSigningKeyLength']) ? DkimSigningKeyLength::UNKNOWN_TO_SDK : (string) $json['NextSigningKeyLength']) : null,
            'CurrentSigningKeyLength' => isset($json['CurrentSigningKeyLength']) ? (!DkimSigningKeyLength::exists((string) $json['CurrentSigningKeyLength']) ? DkimSigningKeyLength::UNKNOWN_TO_SDK : (string) $json['CurrentSigningKeyLength']) : null,
            'LastKeyGenerationTimestamp' => isset($json['LastKeyGenerationTimestamp']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastKeyGenerationTimestamp']))) ? $d : null,
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultDnsTokenList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }
}
