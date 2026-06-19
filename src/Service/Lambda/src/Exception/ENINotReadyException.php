<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ServerException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Lambda couldn't invoke the Lambda function because the elastic network interface (ENI) configured for its VPC
 * connection isn't ready yet. Wait a few moments and try the request again. For more information about VPC
 * configuration, see Configuring a Lambda function to access resources in a VPC [^1].
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-vpc.html
 */
final class ENINotReadyException extends ServerException
{
    /**
     * The exception type.
     *
     * @var string|null
     */
    private $type;

    public function getType(): ?string
    {
        return $this->type;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->type = isset($data['Type']) ? (string) $data['Type'] : null;
    }
}
