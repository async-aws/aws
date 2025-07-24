<?php

namespace AsyncAws\Comprehend\Exception;

use AsyncAws\Comprehend\Enum\InvalidRequestReason;
use AsyncAws\Comprehend\ValueObject\InvalidRequestDetail;
use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request is invalid.
 */
final class InvalidRequestException extends ClientException
{
    /**
     * @var InvalidRequestReason::*|string|null
     */
    private $reason;

    /**
     * @var InvalidRequestDetail|null
     */
    private $detail;

    public function getDetail(): ?InvalidRequestDetail
    {
        return $this->detail;
    }

    /**
     * @return InvalidRequestReason::*|string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->reason = isset($data['Reason']) ? (string) $data['Reason'] : null;
        $this->detail = empty($data['Detail']) ? null : $this->populateResultInvalidRequestDetail($data['Detail']);
    }

    private function populateResultInvalidRequestDetail(array $json): InvalidRequestDetail
    {
        return new InvalidRequestDetail([
            'Reason' => isset($json['Reason']) ? (string) $json['Reason'] : null,
        ]);
    }
}
