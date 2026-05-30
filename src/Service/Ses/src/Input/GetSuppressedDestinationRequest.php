<?php

namespace AsyncAws\Ses\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * A request to retrieve information about an email address that's on the suppression list for your account or for a
 * specific tenant.
 */
final class GetSuppressedDestinationRequest extends Input
{
    /**
     * The email address that's on the suppression list for your account or for the specified tenant.
     *
     * @required
     *
     * @var string|null
     */
    private $emailAddress;

    /**
     * The name of the tenant whose suppression list you want to query. If you omit this parameter, the operation targets
     * the account-level suppression list.
     *
     * @var string|null
     */
    private $tenantName;

    /**
     * @param array{
     *   EmailAddress?: string,
     *   TenantName?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->emailAddress = $input['EmailAddress'] ?? null;
        $this->tenantName = $input['TenantName'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   EmailAddress?: string,
     *   TenantName?: string|null,
     *   '@region'?: string|null,
     * }|GetSuppressedDestinationRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function getTenantName(): ?string
    {
        return $this->tenantName;
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
        if (null !== $this->tenantName) {
            $query['TenantName'] = $this->tenantName;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->emailAddress) {
            throw new InvalidArgument(\sprintf('Missing parameter "EmailAddress" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['EmailAddress'] = $v;
        $uriString = '/v2/email/suppression/addresses/' . rawurlencode($uri['EmailAddress']);

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setEmailAddress(?string $value): self
    {
        $this->emailAddress = $value;

        return $this;
    }

    public function setTenantName(?string $value): self
    {
        $this->tenantName = $value;

        return $this;
    }
}
