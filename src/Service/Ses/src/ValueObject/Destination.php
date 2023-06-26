<?php

namespace AsyncAws\Ses\ValueObject;

/**
 * An object that describes the recipients for an email.
 *
 * > Amazon SES does not support the SMTPUTF8 extension, as described in RFC6531 [^1]. For this reason, the *local part*
 * > of a destination email address (the part of the email address that precedes the @ sign) may only contain 7-bit
 * > ASCII characters [^2]. If the *domain part* of an address (the part after the @ sign) contains non-ASCII
 * > characters, they must be encoded using Punycode, as described in RFC3492 [^3].
 *
 * [^1]: https://tools.ietf.org/html/rfc6531
 * [^2]: https://en.wikipedia.org/wiki/Email_address#Local-part
 * [^3]: https://tools.ietf.org/html/rfc3492.html
 */
final class Destination
{
    /**
     * An array that contains the email addresses of the "To" recipients for the email.
     *
     * @var string[]|null
     */
    private $toAddresses;

    /**
     * An array that contains the email addresses of the "CC" (carbon copy) recipients for the email.
     *
     * @var string[]|null
     */
    private $ccAddresses;

    /**
     * An array that contains the email addresses of the "BCC" (blind carbon copy) recipients for the email.
     *
     * @var string[]|null
     */
    private $bccAddresses;

    /**
     * @param array{
     *   ToAddresses?: null|string[],
     *   CcAddresses?: null|string[],
     *   BccAddresses?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->toAddresses = $input['ToAddresses'] ?? null;
        $this->ccAddresses = $input['CcAddresses'] ?? null;
        $this->bccAddresses = $input['BccAddresses'] ?? null;
    }

    /**
     * @param array{
     *   ToAddresses?: null|string[],
     *   CcAddresses?: null|string[],
     *   BccAddresses?: null|string[],
     * }|Destination $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getBccAddresses(): array
    {
        return $this->bccAddresses ?? [];
    }

    /**
     * @return string[]
     */
    public function getCcAddresses(): array
    {
        return $this->ccAddresses ?? [];
    }

    /**
     * @return string[]
     */
    public function getToAddresses(): array
    {
        return $this->toAddresses ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->toAddresses) {
            $index = -1;
            $payload['ToAddresses'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['ToAddresses'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->ccAddresses) {
            $index = -1;
            $payload['CcAddresses'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['CcAddresses'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->bccAddresses) {
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
