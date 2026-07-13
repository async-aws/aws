<?php

namespace AsyncAws\Ses\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Ses\Enum\DkimStatus;

/**
 * If the action is successful, the service sends back an HTTP 200 response.
 *
 * The following data is returned in JSON format by the service.
 */
class PutEmailIdentityDkimSigningAttributesResponse extends Result
{
    /**
     * The DKIM authentication status of the identity. Amazon SES determines the authentication status by searching for
     * specific records in the DNS configuration for your domain. If you used Easy DKIM [^1] to set up DKIM authentication,
     * Amazon SES tries to find three unique CNAME records in the DNS configuration for your domain.
     *
     * If you provided a public key to perform DKIM authentication, Amazon SES tries to find a TXT record that uses the
     * selector that you specified. The value of the TXT record must be a public key that's paired with the private key that
     * you specified in the process of creating the identity.
     *
     * The status can be one of the following:
     *
     * - `PENDING` – The verification process was initiated, but Amazon SES hasn't yet detected the DKIM records in the
     *   DNS configuration for the domain.
     * - `SUCCESS` – The verification process completed successfully.
     * - `FAILED` – The verification process failed. This typically occurs when Amazon SES fails to find the DKIM records
     *   in the DNS configuration of the domain.
     * - `TEMPORARY_FAILURE` – A temporary issue is preventing Amazon SES from determining the DKIM authentication status
     *   of the domain.
     * - `NOT_STARTED` – The DKIM verification process hasn't been initiated for the domain.
     *
     * [^1]: https://docs.aws.amazon.com/ses/latest/DeveloperGuide/easy-dkim.html
     *
     * @var DkimStatus::*|null
     */
    private $dkimStatus;

    /**
     * If you used Easy DKIM [^1] to configure DKIM authentication for the domain, then this object contains a set of unique
     * strings that you use to create a set of CNAME records that you add to the DNS configuration for your domain. When
     * Amazon SES detects these records in the DNS configuration for your domain, the DKIM authentication process is
     * complete.
     *
     * If you configured DKIM authentication for the domain by providing your own public-private key pair, then this object
     * contains the selector that's associated with your public key.
     *
     * Regardless of the DKIM authentication method you use, Amazon SES searches for the appropriate records in the DNS
     * configuration of the domain for up to 72 hours.
     *
     * [^1]: https://docs.aws.amazon.com/ses/latest/DeveloperGuide/easy-dkim.html
     *
     * @var string[]
     */
    private $dkimTokens;

    /**
     * The hosted zone where Amazon SES publishes the DKIM public key TXT records for this email identity. This value
     * indicates the DNS zone that customers must reference when configuring their CNAME records for DKIM authentication.
     *
     * When configuring DKIM for your domain, create CNAME records in your DNS that point to the selectors in this hosted
     * zone. For example:
     *
     * ` selector1._domainkey.yourdomain.com CNAME selector1.<SigningHostedZone> `
     *
     * ` selector2._domainkey.yourdomain.com CNAME selector2.<SigningHostedZone> `
     *
     * ` selector3._domainkey.yourdomain.com CNAME selector3.<SigningHostedZone> `
     *
     * @var string|null
     */
    private $signingHostedZone;

    /**
     * @return DkimStatus::*|null
     */
    public function getDkimStatus(): ?string
    {
        $this->initialize();

        return $this->dkimStatus;
    }

    /**
     * @return string[]
     */
    public function getDkimTokens(): array
    {
        $this->initialize();

        return $this->dkimTokens;
    }

    public function getSigningHostedZone(): ?string
    {
        $this->initialize();

        return $this->signingHostedZone;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->dkimStatus = isset($data['DkimStatus']) ? (!DkimStatus::exists((string) $data['DkimStatus']) ? DkimStatus::UNKNOWN_TO_SDK : (string) $data['DkimStatus']) : null;
        $this->dkimTokens = empty($data['DkimTokens']) ? [] : $this->populateResultDnsTokenList($data['DkimTokens']);
        $this->signingHostedZone = isset($data['SigningHostedZone']) ? (string) $data['SigningHostedZone'] : null;
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
