<?php

namespace AsyncAws\Ses\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Ses\Enum\BehaviorOnMxFailure;
use AsyncAws\Ses\Enum\DkimSigningAttributesOrigin;
use AsyncAws\Ses\Enum\DkimSigningKeyLength;
use AsyncAws\Ses\Enum\DkimStatus;
use AsyncAws\Ses\Enum\IdentityType;
use AsyncAws\Ses\Enum\MailFromDomainStatus;
use AsyncAws\Ses\Enum\VerificationError;
use AsyncAws\Ses\Enum\VerificationStatus;
use AsyncAws\Ses\ValueObject\DkimAttributes;
use AsyncAws\Ses\ValueObject\MailFromAttributes;
use AsyncAws\Ses\ValueObject\SOARecord;
use AsyncAws\Ses\ValueObject\Tag;
use AsyncAws\Ses\ValueObject\VerificationInfo;

/**
 * Details about an email identity.
 */
class GetEmailIdentityResponse extends Result
{
    /**
     * The email identity type. Note: the `MANAGED_DOMAIN` identity type is not supported.
     *
     * @var IdentityType::*|null
     */
    private $identityType;

    /**
     * The feedback forwarding configuration for the identity.
     *
     * If the value is `true`, you receive email notifications when bounce or complaint events occur. These notifications
     * are sent to the address that you specified in the `Return-Path` header of the original email.
     *
     * You're required to have a method of tracking bounces and complaints. If you haven't set up another mechanism for
     * receiving bounce or complaint notifications (for example, by setting up an event destination), you receive an email
     * notification when these events occur (even if this setting is disabled).
     *
     * @var bool|null
     */
    private $feedbackForwardingStatus;

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

    /**
     * An object that contains information about the Mail-From attributes for the email identity.
     *
     * @var MailFromAttributes|null
     */
    private $mailFromAttributes;

    /**
     * A map of policy names to policies.
     *
     * @var array<string, string>
     */
    private $policies;

    /**
     * An array of objects that define the tags (keys and values) that are associated with the email identity.
     *
     * @var Tag[]
     */
    private $tags;

    /**
     * The configuration set used by default when sending from this identity.
     *
     * @var string|null
     */
    private $configurationSetName;

    /**
     * The verification status of the identity. The status can be one of the following:
     *
     * - `PENDING` – The verification process was initiated, but Amazon SES hasn't yet been able to verify the identity.
     * - `SUCCESS` – The verification process completed successfully.
     * - `FAILED` – The verification process failed.
     * - `TEMPORARY_FAILURE` – A temporary issue is preventing Amazon SES from determining the verification status of the
     *   identity.
     * - `NOT_STARTED` – The verification process hasn't been initiated for the identity.
     *
     * @var VerificationStatus::*|null
     */
    private $verificationStatus;

    /**
     * An object that contains additional information about the verification status for the identity.
     *
     * @var VerificationInfo|null
     */
    private $verificationInfo;

    public function getConfigurationSetName(): ?string
    {
        $this->initialize();

        return $this->configurationSetName;
    }

    public function getDkimAttributes(): ?DkimAttributes
    {
        $this->initialize();

        return $this->dkimAttributes;
    }

    public function getFeedbackForwardingStatus(): ?bool
    {
        $this->initialize();

        return $this->feedbackForwardingStatus;
    }

    /**
     * @return IdentityType::*|null
     */
    public function getIdentityType(): ?string
    {
        $this->initialize();

        return $this->identityType;
    }

    public function getMailFromAttributes(): ?MailFromAttributes
    {
        $this->initialize();

        return $this->mailFromAttributes;
    }

    /**
     * @return array<string, string>
     */
    public function getPolicies(): array
    {
        $this->initialize();

        return $this->policies;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        $this->initialize();

        return $this->tags;
    }

    public function getVerificationInfo(): ?VerificationInfo
    {
        $this->initialize();

        return $this->verificationInfo;
    }

    /**
     * @return VerificationStatus::*|null
     */
    public function getVerificationStatus(): ?string
    {
        $this->initialize();

        return $this->verificationStatus;
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
        $this->feedbackForwardingStatus = isset($data['FeedbackForwardingStatus']) ? filter_var($data['FeedbackForwardingStatus'], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->verifiedForSendingStatus = isset($data['VerifiedForSendingStatus']) ? filter_var($data['VerifiedForSendingStatus'], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->dkimAttributes = empty($data['DkimAttributes']) ? null : $this->populateResultDkimAttributes($data['DkimAttributes']);
        $this->mailFromAttributes = empty($data['MailFromAttributes']) ? null : $this->populateResultMailFromAttributes($data['MailFromAttributes']);
        $this->policies = empty($data['Policies']) ? [] : $this->populateResultPolicyMap($data['Policies']);
        $this->tags = empty($data['Tags']) ? [] : $this->populateResultTagList($data['Tags']);
        $this->configurationSetName = isset($data['ConfigurationSetName']) ? (string) $data['ConfigurationSetName'] : null;
        $this->verificationStatus = isset($data['VerificationStatus']) ? (!VerificationStatus::exists((string) $data['VerificationStatus']) ? VerificationStatus::UNKNOWN_TO_SDK : (string) $data['VerificationStatus']) : null;
        $this->verificationInfo = empty($data['VerificationInfo']) ? null : $this->populateResultVerificationInfo($data['VerificationInfo']);
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

    private function populateResultMailFromAttributes(array $json): MailFromAttributes
    {
        return new MailFromAttributes([
            'MailFromDomain' => (string) $json['MailFromDomain'],
            'MailFromDomainStatus' => !MailFromDomainStatus::exists((string) $json['MailFromDomainStatus']) ? MailFromDomainStatus::UNKNOWN_TO_SDK : (string) $json['MailFromDomainStatus'],
            'BehaviorOnMxFailure' => !BehaviorOnMxFailure::exists((string) $json['BehaviorOnMxFailure']) ? BehaviorOnMxFailure::UNKNOWN_TO_SDK : (string) $json['BehaviorOnMxFailure'],
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function populateResultPolicyMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (string) $value;
        }

        return $items;
    }

    private function populateResultSOARecord(array $json): SOARecord
    {
        return new SOARecord([
            'PrimaryNameServer' => isset($json['PrimaryNameServer']) ? (string) $json['PrimaryNameServer'] : null,
            'AdminEmail' => isset($json['AdminEmail']) ? (string) $json['AdminEmail'] : null,
            'SerialNumber' => isset($json['SerialNumber']) ? (int) $json['SerialNumber'] : null,
        ]);
    }

    private function populateResultTag(array $json): Tag
    {
        return new Tag([
            'Key' => (string) $json['Key'],
            'Value' => (string) $json['Value'],
        ]);
    }

    /**
     * @return Tag[]
     */
    private function populateResultTagList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultTag($item);
        }

        return $items;
    }

    private function populateResultVerificationInfo(array $json): VerificationInfo
    {
        return new VerificationInfo([
            'LastCheckedTimestamp' => isset($json['LastCheckedTimestamp']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastCheckedTimestamp']))) ? $d : null,
            'LastSuccessTimestamp' => isset($json['LastSuccessTimestamp']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastSuccessTimestamp']))) ? $d : null,
            'ErrorType' => isset($json['ErrorType']) ? (!VerificationError::exists((string) $json['ErrorType']) ? VerificationError::UNKNOWN_TO_SDK : (string) $json['ErrorType']) : null,
            'SOARecord' => empty($json['SOARecord']) ? null : $this->populateResultSOARecord($json['SOARecord']),
        ]);
    }
}
