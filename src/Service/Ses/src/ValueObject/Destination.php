<?php

namespace AsyncAws\Ses\ValueObject;

/**
 * An object that contains the recipients of the email message.
 */
final class Destination
{
    /**
     * An array that contains the email addresses of the "To" recipients for the email.
     */
    private $ToAddresses;

    /**
     * An array that contains the email addresses of the "CC" (carbon copy) recipients for the email.
     */
    private $CcAddresses;

    /**
     * An array that contains the email addresses of the "BCC" (blind carbon copy) recipients for the email.
     */
    private $BccAddresses;

    /**
     * @param array{
     *   ToAddresses?: null|string[],
     *   CcAddresses?: null|string[],
     *   BccAddresses?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ToAddresses = $input['ToAddresses'] ?? null;
        $this->CcAddresses = $input['CcAddresses'] ?? null;
        $this->BccAddresses = $input['BccAddresses'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getBccAddresses(): array
    {
        return $this->BccAddresses ?? [];
    }

    /**
     * @return string[]
     */
    public function getCcAddresses(): array
    {
        return $this->CcAddresses ?? [];
    }

    /**
     * @return string[]
     */
    public function getToAddresses(): array
    {
        return $this->ToAddresses ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->ToAddresses) {
            $index = -1;
            $payload['ToAddresses'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['ToAddresses'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->CcAddresses) {
            $index = -1;
            $payload['CcAddresses'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['CcAddresses'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->BccAddresses) {
            $index = -1;
            $payload['BccAddresses'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['BccAddresses'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
