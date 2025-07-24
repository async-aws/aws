<?php

namespace AsyncAws\LocationService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\LocationService\Enum\ValidationExceptionReason;
use AsyncAws\LocationService\ValueObject\ValidationExceptionField;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The input failed to meet the constraints specified by the AWS service.
 */
final class ValidationException extends ClientException
{
    /**
     * A message with the reason for the validation exception error.
     *
     * @var ValidationExceptionReason::*|string
     */
    private $reason;

    /**
     * The field where the invalid entry was detected.
     *
     * @var ValidationExceptionField[]
     */
    private $fieldList;

    /**
     * @return ValidationExceptionField[]
     */
    public function getFieldList(): array
    {
        return $this->fieldList;
    }

    /**
     * @return ValidationExceptionReason::*|string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->reason = (string) $data['reason'];
        $this->fieldList = $this->populateResultValidationExceptionFieldList($data['fieldList'] ?? []);
    }

    private function populateResultValidationExceptionField(array $json): ValidationExceptionField
    {
        return new ValidationExceptionField([
            'Name' => (string) $json['name'],
            'Message' => (string) $json['message'],
        ]);
    }

    /**
     * @return ValidationExceptionField[]
     */
    private function populateResultValidationExceptionFieldList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultValidationExceptionField($item);
        }

        return $items;
    }
}
