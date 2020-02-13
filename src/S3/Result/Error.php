<?php

namespace AsyncAws\S3\Result;

class Error
{
    /**
     * The error key.
     */
    private $Key;

    /**
     * The version ID of the error.
     */
    private $VersionId;

    /**
     * The error code is a string that uniquely identifies an error condition. It is meant to be read and understood by
     * programs that detect and handle errors by type.
     */
    private $Code;

    /**
     * The error message contains a generic description of the error condition in English. It is intended for a human
     * audience. Simple programs display the message directly to the end user if they encounter an error condition they
     * don't know how or don't care to handle. Sophisticated programs with more exhaustive error handling and proper
     * internationalization are more likely to ignore the error message.
     */
    private $Message;

    /**
     * @param array{
     *   Key: ?string,
     *   VersionId: ?string,
     *   Code: ?string,
     *   Message: ?string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Key = $input['Key'];
        $this->VersionId = $input['VersionId'];
        $this->Code = $input['Code'];
        $this->Message = $input['Message'];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCode(): ?string
    {
        return $this->Code;
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    public function getMessage(): ?string
    {
        return $this->Message;
    }

    public function getVersionId(): ?string
    {
        return $this->VersionId;
    }
}
