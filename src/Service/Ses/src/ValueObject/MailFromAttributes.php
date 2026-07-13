<?php

namespace AsyncAws\Ses\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Ses\Enum\BehaviorOnMxFailure;
use AsyncAws\Ses\Enum\MailFromDomainStatus;

/**
 * A list of attributes that are associated with a MAIL FROM domain.
 */
final class MailFromAttributes
{
    /**
     * The name of a domain that an email identity uses as a custom MAIL FROM domain.
     *
     * @var string
     */
    private $mailFromDomain;

    /**
     * The status of the MAIL FROM domain. This status can have the following values:
     *
     * - `PENDING` – Amazon SES hasn't started searching for the MX record yet.
     * - `SUCCESS` – Amazon SES detected the required MX record for the MAIL FROM domain.
     * - `FAILED` – Amazon SES can't find the required MX record, or the record no longer exists.
     * - `TEMPORARY_FAILURE` – A temporary issue occurred, which prevented Amazon SES from determining the status of the
     *   MAIL FROM domain.
     *
     * @var MailFromDomainStatus::*
     */
    private $mailFromDomainStatus;

    /**
     * The action to take if the required MX record can't be found when you send an email. When you set this value to
     * `USE_DEFAULT_VALUE`, the mail is sent using *amazonses.com* as the MAIL FROM domain. When you set this value to
     * `REJECT_MESSAGE`, the Amazon SES API v2 returns a `MailFromDomainNotVerified` error, and doesn't attempt to deliver
     * the email.
     *
     * These behaviors are taken when the custom MAIL FROM domain configuration is in the `Pending`, `Failed`, and
     * `TemporaryFailure` states.
     *
     * @var BehaviorOnMxFailure::*
     */
    private $behaviorOnMxFailure;

    /**
     * @param array{
     *   MailFromDomain: string,
     *   MailFromDomainStatus: MailFromDomainStatus::*,
     *   BehaviorOnMxFailure: BehaviorOnMxFailure::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->mailFromDomain = $input['MailFromDomain'] ?? $this->throwException(new InvalidArgument('Missing required field "MailFromDomain".'));
        $this->mailFromDomainStatus = $input['MailFromDomainStatus'] ?? $this->throwException(new InvalidArgument('Missing required field "MailFromDomainStatus".'));
        $this->behaviorOnMxFailure = $input['BehaviorOnMxFailure'] ?? $this->throwException(new InvalidArgument('Missing required field "BehaviorOnMxFailure".'));
    }

    /**
     * @param array{
     *   MailFromDomain: string,
     *   MailFromDomainStatus: MailFromDomainStatus::*,
     *   BehaviorOnMxFailure: BehaviorOnMxFailure::*,
     * }|MailFromAttributes $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return BehaviorOnMxFailure::*
     */
    public function getBehaviorOnMxFailure(): string
    {
        return $this->behaviorOnMxFailure;
    }

    public function getMailFromDomain(): string
    {
        return $this->mailFromDomain;
    }

    /**
     * @return MailFromDomainStatus::*
     */
    public function getMailFromDomainStatus(): string
    {
        return $this->mailFromDomainStatus;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
