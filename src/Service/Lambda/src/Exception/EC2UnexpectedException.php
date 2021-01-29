<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ServerException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * AWS Lambda received an unexpected EC2 client exception while setting up for the Lambda function.
 */
final class EC2UnexpectedException extends ServerException
{
    private $type;

    private $ec2ErrorCode;

    public function getEc2ErrorCode(): ?string
    {
        return $this->ec2ErrorCode;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->type = isset($data['Type']) ? (string) $data['Type'] : null;
        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
        $this->ec2ErrorCode = isset($data['EC2ErrorCode']) ? (string) $data['EC2ErrorCode'] : null;
    }
}
