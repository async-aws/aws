<?php

namespace AsyncAws\S3Vectors\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\S3Vectors\ValueObject\ValidationExceptionField;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The requested action isn't valid.
 */
final class ValidationException extends ClientException
{
    /**
     * A list of specific validation failures that are encountered during input processing. Each entry in the list contains
     * a path to the field that failed validation and a detailed message that explains why the validation failed. To satisfy
     * multiple constraints, a field can appear multiple times in this list if it failed. You can use the information to
     * identify and fix the specific validation issues in your request.
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
            'path' => (string) $json['path'],
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
