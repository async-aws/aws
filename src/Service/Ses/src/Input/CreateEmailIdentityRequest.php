<?php

namespace AsyncAws\Ses\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Ses\ValueObject\DkimSigningAttributes;
use AsyncAws\Ses\ValueObject\Tag;

/**
 * A request to begin the verification process for an email identity (an email address or domain).
 */
final class CreateEmailIdentityRequest extends Input
{
    /**
     * The email address or domain to verify.
     *
     * @required
     *
     * @var string|null
     */
    private $emailIdentity;

    /**
     * An array of objects that define the tags (keys and values) to associate with the email identity.
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * If your request includes this object, Amazon SES configures the identity to use Bring Your Own DKIM (BYODKIM) for
     * DKIM authentication purposes, or, configures the key length to be used for Easy DKIM [^1].
     *
     * You can only specify this object if the email identity is a domain, as opposed to an address.
     *
     * [^1]: https://docs.aws.amazon.com/ses/latest/DeveloperGuide/easy-dkim.html
     *
     * @var DkimSigningAttributes|null
     */
    private $dkimSigningAttributes;

    /**
     * The configuration set to use by default when sending from this identity. Note that any configuration set defined in
     * the email sending request takes precedence.
     *
     * @var string|null
     */
    private $configurationSetName;

    /**
     * @param array{
     *   EmailIdentity?: string,
     *   Tags?: array<Tag|array>|null,
     *   DkimSigningAttributes?: DkimSigningAttributes|array|null,
     *   ConfigurationSetName?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->emailIdentity = $input['EmailIdentity'] ?? null;
        $this->tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
        $this->dkimSigningAttributes = isset($input['DkimSigningAttributes']) ? DkimSigningAttributes::create($input['DkimSigningAttributes']) : null;
        $this->configurationSetName = $input['ConfigurationSetName'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   EmailIdentity?: string,
     *   Tags?: array<Tag|array>|null,
     *   DkimSigningAttributes?: DkimSigningAttributes|array|null,
     *   ConfigurationSetName?: string|null,
     *   '@region'?: string|null,
     * }|CreateEmailIdentityRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConfigurationSetName(): ?string
    {
        return $this->configurationSetName;
    }

    public function getDkimSigningAttributes(): ?DkimSigningAttributes
    {
        return $this->dkimSigningAttributes;
    }

    public function getEmailIdentity(): ?string
    {
        return $this->emailIdentity;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
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
        $uriString = '/v2/email/identities';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setConfigurationSetName(?string $value): self
    {
        $this->configurationSetName = $value;

        return $this;
    }

    public function setDkimSigningAttributes(?DkimSigningAttributes $value): self
    {
        $this->dkimSigningAttributes = $value;

        return $this;
    }

    public function setEmailIdentity(?string $value): self
    {
        $this->emailIdentity = $value;

        return $this;
    }

    /**
     * @param Tag[] $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->emailIdentity) {
            throw new InvalidArgument(\sprintf('Missing parameter "EmailIdentity" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['EmailIdentity'] = $v;
        if (null !== $v = $this->tags) {
            $index = -1;
            $payload['Tags'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Tags'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->dkimSigningAttributes) {
            $payload['DkimSigningAttributes'] = $v->requestBody();
        }
        if (null !== $v = $this->configurationSetName) {
            $payload['ConfigurationSetName'] = $v;
        }

        return $payload;
    }
}
