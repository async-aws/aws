<?php

namespace AsyncAws\SsoOidc\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class StartDeviceAuthorizationResponse extends Result
{
    /**
     * The short-lived code that is used by the device when polling for a session token.
     *
     * @var string|null
     */
    private $deviceCode;

    /**
     * A one-time user verification code. This is needed to authorize an in-use device.
     *
     * @var string|null
     */
    private $userCode;

    /**
     * The URI of the verification page that takes the `userCode` to authorize the device.
     *
     * @var string|null
     */
    private $verificationUri;

    /**
     * An alternate URL that the client can use to automatically launch a browser. This process skips the manual step in
     * which the user visits the verification page and enters their code.
     *
     * @var string|null
     */
    private $verificationUriComplete;

    /**
     * Indicates the number of seconds in which the verification code will become invalid.
     *
     * @var int|null
     */
    private $expiresIn;

    /**
     * Indicates the number of seconds the client must wait between attempts when polling for a session.
     *
     * @var int|null
     */
    private $interval;

    public function getDeviceCode(): ?string
    {
        $this->initialize();

        return $this->deviceCode;
    }

    public function getExpiresIn(): ?int
    {
        $this->initialize();

        return $this->expiresIn;
    }

    public function getInterval(): ?int
    {
        $this->initialize();

        return $this->interval;
    }

    public function getUserCode(): ?string
    {
        $this->initialize();

        return $this->userCode;
    }

    public function getVerificationUri(): ?string
    {
        $this->initialize();

        return $this->verificationUri;
    }

    public function getVerificationUriComplete(): ?string
    {
        $this->initialize();

        return $this->verificationUriComplete;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->deviceCode = isset($data['deviceCode']) ? (string) $data['deviceCode'] : null;
        $this->userCode = isset($data['userCode']) ? (string) $data['userCode'] : null;
        $this->verificationUri = isset($data['verificationUri']) ? (string) $data['verificationUri'] : null;
        $this->verificationUriComplete = isset($data['verificationUriComplete']) ? (string) $data['verificationUriComplete'] : null;
        $this->expiresIn = isset($data['expiresIn']) ? (int) $data['expiresIn'] : null;
        $this->interval = isset($data['interval']) ? (int) $data['interval'] : null;
    }
}
