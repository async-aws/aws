<?php

namespace AsyncAws\Athena\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetCalculationExecutionStatusRequest extends Input
{
    /**
     * The calculation execution UUID.
     *
     * @required
     *
     * @var string|null
     */
    private $calculationExecutionId;

    /**
     * @param array{
     *   CalculationExecutionId?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->calculationExecutionId = $input['CalculationExecutionId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCalculationExecutionId(): ?string
    {
        return $this->calculationExecutionId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AmazonAthena.GetCalculationExecutionStatus',
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

    public function setCalculationExecutionId(?string $value): self
    {
        $this->calculationExecutionId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->calculationExecutionId) {
            throw new InvalidArgument(sprintf('Missing parameter "CalculationExecutionId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['CalculationExecutionId'] = $v;

        return $payload;
    }
}
