<?php

namespace AsyncAws\Ec2\ValueObject;

use AsyncAws\Ec2\Enum\SeverityLabel;

/**
 * The Amazon Inspector scan results for an AMI. This information is present only for AMIs that Amazon Inspector has
 * scanned.
 *
 * When you share an AMI with other accounts, organizations, or organizational units (OUs), or make it public, its scan
 * results are available to the accounts that can use the AMI.
 *
 * The fix-available dates are specific to the AMI. Each date reflects when a fix became available for a finding on that
 * AMI, not when the vendor released the fix. As a result, a date can be later than the vendor's release date, and AMIs
 * with the same vulnerability can have different dates.
 */
final class ScanResults
{
    /**
     * The highest severity among the AMI's Amazon Inspector findings, whether or not a fix is available.
     *
     * The valid values are the following Amazon Inspector severity levels, which correspond to CVSS score ranges:
     *
     * - `informational` - CVSS score of 0.
     * - `low` - CVSS score of 0.1 to 3.9.
     * - `medium` - CVSS score of 4.0 to 6.9.
     * - `high` - CVSS score of 7.0 to 8.9.
     * - `critical` - CVSS score of 9.0 to 10.0.
     *
     * For more information, see Software package vulnerability severity [^1] in the *Amazon Inspector User Guide*.
     *
     * This value is present only if the AMI has at least one finding.
     *
     * [^1]: https://docs.aws.amazon.com/inspector/latest/user/findings-understanding-severity.html#findings-understanding-package-score
     *
     * @var SeverityLabel::*|null
     */
    private $highestFindingSeverityLabel;

    /**
     * The date and time, in the ISO 8601 format in the UTC time zone (YYYY-MM-DDThh:mm:ss.sssZ), of the most recent Amazon
     * Inspector scan of the AMI.
     *
     * @var \DateTimeImmutable|null
     */
    private $findingScanDate;

    /**
     * The earliest date and time, in the ISO 8601 format in the UTC time zone (YYYY-MM-DDThh:mm:ss.sssZ), at which Amazon
     * Inspector reported a fix as available for an `informational` severity finding on the AMI. This value is present only
     * if the AMI has at least one `informational` severity finding with a fix available. Amazon Inspector recomputes this
     * value on each scan.
     *
     * @var \DateTimeImmutable|null
     */
    private $informationalFixAvailableSince;

    /**
     * The earliest date and time, in the ISO 8601 format in the UTC time zone (YYYY-MM-DDThh:mm:ss.sssZ), at which Amazon
     * Inspector reported a fix as available for a `low` severity finding on the AMI. This value is present only if the AMI
     * has at least one `low` severity finding with a fix available. Amazon Inspector recomputes this value on each scan.
     *
     * @var \DateTimeImmutable|null
     */
    private $lowFixAvailableSince;

    /**
     * The earliest date and time, in the ISO 8601 format in the UTC time zone (YYYY-MM-DDThh:mm:ss.sssZ), at which Amazon
     * Inspector reported a fix as available for a `medium` severity finding on the AMI. This value is present only if the
     * AMI has at least one `medium` severity finding with a fix available. Amazon Inspector recomputes this value on each
     * scan.
     *
     * @var \DateTimeImmutable|null
     */
    private $mediumFixAvailableSince;

    /**
     * The earliest date and time, in the ISO 8601 format in the UTC time zone (YYYY-MM-DDThh:mm:ss.sssZ), at which Amazon
     * Inspector reported a fix as available for a `high` severity finding on the AMI. This value is present only if the AMI
     * has at least one `high` severity finding with a fix available. Amazon Inspector recomputes this value on each scan.
     *
     * @var \DateTimeImmutable|null
     */
    private $highFixAvailableSince;

    /**
     * The earliest date and time, in the ISO 8601 format in the UTC time zone (YYYY-MM-DDThh:mm:ss.sssZ), at which Amazon
     * Inspector reported a fix as available for a `critical` severity finding on the AMI. This value is present only if the
     * AMI has at least one `critical` severity finding with a fix available. Amazon Inspector recomputes this value on each
     * scan.
     *
     * @var \DateTimeImmutable|null
     */
    private $criticalFixAvailableSince;

    /**
     * @param array{
     *   HighestFindingSeverityLabel?: SeverityLabel::*|null,
     *   FindingScanDate?: \DateTimeImmutable|null,
     *   InformationalFixAvailableSince?: \DateTimeImmutable|null,
     *   LowFixAvailableSince?: \DateTimeImmutable|null,
     *   MediumFixAvailableSince?: \DateTimeImmutable|null,
     *   HighFixAvailableSince?: \DateTimeImmutable|null,
     *   CriticalFixAvailableSince?: \DateTimeImmutable|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->highestFindingSeverityLabel = $input['HighestFindingSeverityLabel'] ?? null;
        $this->findingScanDate = $input['FindingScanDate'] ?? null;
        $this->informationalFixAvailableSince = $input['InformationalFixAvailableSince'] ?? null;
        $this->lowFixAvailableSince = $input['LowFixAvailableSince'] ?? null;
        $this->mediumFixAvailableSince = $input['MediumFixAvailableSince'] ?? null;
        $this->highFixAvailableSince = $input['HighFixAvailableSince'] ?? null;
        $this->criticalFixAvailableSince = $input['CriticalFixAvailableSince'] ?? null;
    }

    /**
     * @param array{
     *   HighestFindingSeverityLabel?: SeverityLabel::*|null,
     *   FindingScanDate?: \DateTimeImmutable|null,
     *   InformationalFixAvailableSince?: \DateTimeImmutable|null,
     *   LowFixAvailableSince?: \DateTimeImmutable|null,
     *   MediumFixAvailableSince?: \DateTimeImmutable|null,
     *   HighFixAvailableSince?: \DateTimeImmutable|null,
     *   CriticalFixAvailableSince?: \DateTimeImmutable|null,
     * }|ScanResults $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCriticalFixAvailableSince(): ?\DateTimeImmutable
    {
        return $this->criticalFixAvailableSince;
    }

    public function getFindingScanDate(): ?\DateTimeImmutable
    {
        return $this->findingScanDate;
    }

    public function getHighFixAvailableSince(): ?\DateTimeImmutable
    {
        return $this->highFixAvailableSince;
    }

    /**
     * @return SeverityLabel::*|null
     */
    public function getHighestFindingSeverityLabel(): ?string
    {
        return $this->highestFindingSeverityLabel;
    }

    public function getInformationalFixAvailableSince(): ?\DateTimeImmutable
    {
        return $this->informationalFixAvailableSince;
    }

    public function getLowFixAvailableSince(): ?\DateTimeImmutable
    {
        return $this->lowFixAvailableSince;
    }

    public function getMediumFixAvailableSince(): ?\DateTimeImmutable
    {
        return $this->mediumFixAvailableSince;
    }
}
