<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\CognitoIdentityProvider\ValueObject\SMSMfaSettingsType;
use AsyncAws\CognitoIdentityProvider\ValueObject\SoftwareTokenMfaSettingsType;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class SetUserMFAPreferenceRequest extends Input
{
    /**
     * The SMS text message multi-factor authentication (MFA) settings.
     *
     * @var SMSMfaSettingsType|null
     */
    private $SMSMfaSettings;

    /**
     * The time-based one-time password software token MFA settings.
     *
     * @var SoftwareTokenMfaSettingsType|null
     */
    private $SoftwareTokenMfaSettings;

    /**
     * The access token for the user.
     *
     * @required
     *
     * @var string|null
     */
    private $AccessToken;

    /**
     * @param array{
     *   SMSMfaSettings?: SMSMfaSettingsType|array,
     *   SoftwareTokenMfaSettings?: SoftwareTokenMfaSettingsType|array,
     *   AccessToken?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->SMSMfaSettings = isset($input['SMSMfaSettings']) ? SMSMfaSettingsType::create($input['SMSMfaSettings']) : null;
        $this->SoftwareTokenMfaSettings = isset($input['SoftwareTokenMfaSettings']) ? SoftwareTokenMfaSettingsType::create($input['SoftwareTokenMfaSettings']) : null;
        $this->AccessToken = $input['AccessToken'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessToken(): ?string
    {
        return $this->AccessToken;
    }

    public function getSMSMfaSettings(): ?SMSMfaSettingsType
    {
        return $this->SMSMfaSettings;
    }

    public function getSoftwareTokenMfaSettings(): ?SoftwareTokenMfaSettingsType
    {
        return $this->SoftwareTokenMfaSettings;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.SetUserMFAPreference',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAccessToken(?string $value): self
    {
        $this->AccessToken = $value;

        return $this;
    }

    public function setSMSMfaSettings(?SMSMfaSettingsType $value): self
    {
        $this->SMSMfaSettings = $value;

        return $this;
    }

    public function setSoftwareTokenMfaSettings(?SoftwareTokenMfaSettingsType $value): self
    {
        $this->SoftwareTokenMfaSettings = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->SMSMfaSettings) {
            $payload['SMSMfaSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->SoftwareTokenMfaSettings) {
            $payload['SoftwareTokenMfaSettings'] = $v->requestBody();
        }
        if (null === $v = $this->AccessToken) {
            throw new InvalidArgument(sprintf('Missing parameter "AccessToken" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['AccessToken'] = $v;

        return $payload;
    }
}
