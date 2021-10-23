<?php

namespace AsyncAws\Route53\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * The input for a GetChange request.
 */
final class GetChangeRequest extends Input
{
    /**
     * The ID of the change batch request. The value that you specify here is the value that `ChangeResourceRecordSets`
     * returned in the `Id` element when you submitted the request.
     *
     * @required
     *
     * @var string|null
     */
    private $id;

    /**
     * @param array{
     *   Id?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->id = $input['Id'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->id) {
            throw new InvalidArgument(sprintf('Missing parameter "Id" for "%s". The value cannot be null.', __CLASS__));
        }
        $v = preg_replace('#^(/hostedzone/|/change/|/delegationset/)#', '', $v);
        $uri['Id'] = $v;
        $uriString = '/2013-04-01/change/' . rawurlencode($uri['Id']);

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setId(?string $value): self
    {
        $this->id = $value;

        return $this;
    }
}
