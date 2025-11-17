<?php

namespace AsyncAws\BedrockAgent\Exception;

use AsyncAws\BedrockAgent\ValueObject\ValidationExceptionField;
use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Input validation failed. Check your request parameters and retry the request.
 */
final class ValidationException extends ClientException
{
    /**
     * A list of objects containing fields that caused validation errors and their corresponding validation error messages.
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

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

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
