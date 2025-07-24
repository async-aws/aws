<?php

namespace AsyncAws\AppSync\Exception;

use AsyncAws\AppSync\Enum\BadRequestReason;
use AsyncAws\AppSync\ValueObject\BadRequestDetail;
use AsyncAws\AppSync\ValueObject\CodeError;
use AsyncAws\AppSync\ValueObject\CodeErrorLocation;
use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request is not well formed. For example, a value is invalid or a required field is missing. Check the field
 * values, and then try again.
 */
final class BadRequestException extends ClientException
{
    /**
     * @var BadRequestReason::*|string|null
     */
    private $reason;

    /**
     * @var BadRequestDetail|null
     */
    private $detail;

    public function getDetail(): ?BadRequestDetail
    {
        return $this->detail;
    }

    /**
     * @return BadRequestReason::*|string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->reason = isset($data['reason']) ? (string) $data['reason'] : null;
        $this->detail = empty($data['detail']) ? null : $this->populateResultBadRequestDetail($data['detail']);
    }

    private function populateResultBadRequestDetail(array $json): BadRequestDetail
    {
        return new BadRequestDetail([
            'codeErrors' => !isset($json['codeErrors']) ? null : $this->populateResultCodeErrors($json['codeErrors']),
        ]);
    }

    private function populateResultCodeError(array $json): CodeError
    {
        return new CodeError([
            'errorType' => isset($json['errorType']) ? (string) $json['errorType'] : null,
            'value' => isset($json['value']) ? (string) $json['value'] : null,
            'location' => empty($json['location']) ? null : $this->populateResultCodeErrorLocation($json['location']),
        ]);
    }

    private function populateResultCodeErrorLocation(array $json): CodeErrorLocation
    {
        return new CodeErrorLocation([
            'line' => isset($json['line']) ? (int) $json['line'] : null,
            'column' => isset($json['column']) ? (int) $json['column'] : null,
            'span' => isset($json['span']) ? (int) $json['span'] : null,
        ]);
    }

    /**
     * @return CodeError[]
     */
    private function populateResultCodeErrors(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultCodeError($item);
        }

        return $items;
    }
}
