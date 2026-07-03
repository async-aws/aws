<?php

namespace AsyncAws\Ses\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * A request to return details about an email identity.
 */
final class GetEmailIdentityRequest extends Input
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
     * @param array{
     *   EmailIdentity?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->emailIdentity = $input['EmailIdentity'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   EmailIdentity?: string,
     *   '@region'?: string|null,
     * }|GetEmailIdentityRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEmailIdentity(): ?string
    {
        return $this->emailIdentity;
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
        $uriString = '/v2/email/identities/' . rawurlencode($uri['EmailIdentity']);

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setEmailIdentity(?string $value): self
    {
        $this->emailIdentity = $value;

        return $this;
    }
}
