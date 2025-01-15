<?php

namespace AsyncAws\Ses\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * A request to retrieve information about an email address that's on the suppression list for your account.
 */
final class GetSuppressedDestinationRequest extends Input
{
    /**
     * The email address that's on the account suppression list.
     *
     * @required
     *
     * @var string|null
     */
    private $emailAddress;

    /**
     * @param array{
     *   EmailAddress?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->emailAddress = $input['EmailAddress'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   EmailAddress?: string,
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
}
