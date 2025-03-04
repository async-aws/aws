<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\CognitoIdentityProvider\ValueObject\EmailMfaSettingsType;
use AsyncAws\CognitoIdentityProvider\ValueObject\SMSMfaSettingsType;
use AsyncAws\CognitoIdentityProvider\ValueObject\SoftwareTokenMfaSettingsType;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class SetUserMFAPreferenceRequest extends Input
{
    /**
     * User preferences for SMS message MFA. Activates or deactivates SMS MFA and sets it as the preferred MFA method when
     * multiple methods are available.
     *
     * @var SMSMfaSettingsType|null
     */
    private $smsMfaSettings;

    /**
     * User preferences for time-based one-time password (TOTP) MFA. Activates or deactivates TOTP MFA and sets it as the
     * preferred MFA method when multiple methods are available.
     *
     * @var SoftwareTokenMfaSettingsType|null
     */
    private $softwareTokenMfaSettings;

    /**
     * User preferences for email message MFA. Activates or deactivates email MFA and sets it as the preferred MFA method
     * when multiple methods are available. To activate this setting, your user pool must be in the Essentials tier [^1] or
     * higher.
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/feature-plans-features-essentials.html
     *
     * @var EmailMfaSettingsType|null
     */
    private $emailMfaSettings;

    /**
     * A valid access token that Amazon Cognito issued to the currently signed-in user. Must include a scope claim for
     * `aws.cognito.signin.user.admin`.
     *
     * @required
     *
     * @var string|null
     */
    private $accessToken;

    /**
     * @param array{
     *   SMSMfaSettings?: null|SMSMfaSettingsType|array,
     *   SoftwareTokenMfaSettings?: null|SoftwareTokenMfaSettingsType|array,
     *   EmailMfaSettings?: null|EmailMfaSettingsType|array,
     *   AccessToken?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->smsMfaSettings = isset($input['SMSMfaSettings']) ? SMSMfaSettingsType::create($input['SMSMfaSettings']) : null;
        $this->softwareTokenMfaSettings = isset($input['SoftwareTokenMfaSettings']) ? SoftwareTokenMfaSettingsType::create($input['SoftwareTokenMfaSettings']) : null;
        $this->emailMfaSettings = isset($input['EmailMfaSettings']) ? EmailMfaSettingsType::create($input['EmailMfaSettings']) : null;
        $this->accessToken = $input['AccessToken'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   SMSMfaSettings?: null|SMSMfaSettingsType|array,
     *   SoftwareTokenMfaSettings?: null|SoftwareTokenMfaSettingsType|array,
     *   EmailMfaSettings?: null|EmailMfaSettingsType|array,
     *   AccessToken?: string,
     *   '@region'?: string|null,
     * }|SetUserMFAPreferenceRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getEmailMfaSettings(): ?EmailMfaSettingsType
    {
        return $this->emailMfaSettings;
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
            'Accept' => 'application/json',
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

    public function setEmailMfaSettings(?EmailMfaSettingsType $value): self
    {
        $this->emailMfaSettings = $value;

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
        if (null !== $v = $this->emailMfaSettings) {
            $payload['EmailMfaSettings'] = $v->requestBody();
        }
        if (null === $v = $this->accessToken) {
            throw new InvalidArgument(\sprintf('Missing parameter "AccessToken" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['AccessToken'] = $v;

        return $payload;
    }
}
