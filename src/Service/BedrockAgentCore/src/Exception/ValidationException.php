<?php

namespace AsyncAws\BedrockAgentCore\Exception;

use AsyncAws\BedrockAgentCore\Enum\ValidationExceptionReason;
use AsyncAws\BedrockAgentCore\ValueObject\ValidationExceptionField;
use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The exception that occurs when the input fails to satisfy the constraints specified by the service. Check the error
 * message for details about which input parameter is invalid and correct your request.
 */
final class ValidationException extends ClientException
{
    /**
     * @var ValidationExceptionReason::*
     */
    private $reason;

    /**
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
     * @return ValidationExceptionReason::*
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->reason = !ValidationExceptionReason::exists((string) $data['reason']) ? ValidationExceptionReason::UNKNOWN_TO_SDK : (string) $data['reason'];
        $this->fieldList = empty($data['fieldList']) ? [] : $this->populateResultValidationExceptionFieldList($data['fieldList']);
    }

    private function populateResultValidationExceptionField(array $json): ValidationExceptionField
    {
        return new ValidationExceptionField([
            'name' => (string) $json['name'],
            'message' => (string) $json['message'],
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
