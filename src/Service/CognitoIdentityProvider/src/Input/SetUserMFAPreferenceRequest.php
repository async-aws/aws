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
    private $smsMfaSettings;

    /**
     * The time-based one-time password (TOTP) software token MFA settings.
     *
     * @var SoftwareTokenMfaSettingsType|null
     */
    private $softwareTokenMfaSettings;

    /**
     * A valid access token that Amazon Cognito issued to the user whose MFA preference you want to set.
     *
     * @required
     *
     * @var string|null
     */
    private $accessToken;

    /**
     * @param array{
     *   SMSMfaSettings?: SMSMfaSettingsType|array,
     *   SoftwareTokenMfaSettings?: SoftwareTokenMfaSettingsType|array,
     *   AccessToken?: string,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->smsMfaSettings = isset($input['SMSMfaSettings']) ? SMSMfaSettingsType::create($input['SMSMfaSettings']) : null;
        $this->softwareTokenMfaSettings = isset($input['SoftwareTokenMfaSettings']) ? SoftwareTokenMfaSettingsType::create($input['SoftwareTokenMfaSettings']) : null;
        $this->accessToken = $input['AccessToken'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getSmsMfaSettings(): ?SMSMfaSettingsType
    {
        return $this->smsMfaSettings;
    }

    public function getSoftwareTokenMfaSettings(): ?SoftwareTokenMfaSettingsType
    {
        return $this->softwareTokenMfaSettings;
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
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAccessToken(?string $value): self
    {
        $this->accessToken = $value;

        return $this;
    }

    public function setSmsMfaSettings(?SMSMfaSettingsType $value): self
    {
        $this->smsMfaSettings = $value;

        return $this;
    }

    public function setSoftwareTokenMfaSettings(?SoftwareTokenMfaSettingsType $value): self
    {
        $this->softwareTokenMfaSettings = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->smsMfaSettings) {
            $payload['SMSMfaSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->softwareTokenMfaSettings) {
            $payload['SoftwareTokenMfaSettings'] = $v->requestBody();
        }
        if (null === $v = $this->accessToken) {
            throw new InvalidArgument(sprintf('Missing parameter "AccessToken" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['AccessToken'] = $v;

        return $payload;
    }
}
