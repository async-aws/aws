<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class FieldMemberBlobValue extends Field
{
    /**
     * A value of BLOB data type.
     *
     * @var string
     */
    private $blobValue;

    /**
     * @param array{
     *   blobValue: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->blobValue = $input['blobValue'] ?? $this->throwException(new InvalidArgument('Missing required field "blobValue".'));
    }

    public function getBlobValue(): string
    {
        return $this->blobValue;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->blobValue;
        $payload['blobValue'] = base64_encode($v);

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
