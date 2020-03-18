<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\ValueObject\DeletedObject;
use AsyncAws\S3\ValueObject\Error;

class DeleteObjectsOutput extends Result
{
    /**
     * Container element for a successful delete. It identifies the object that was successfully deleted.
     */
    private $Deleted = [];

    private $RequestCharged;

    /**
     * Container for a failed delete operation that describes the object that Amazon S3 attempted to delete and the error it
     * encountered.
     */
    private $Errors = [];

    /**
     * @return DeletedObject[]
     */
    public function getDeleted(): array
    {
        $this->initialize();

        return $this->Deleted;
    }

    /**
     * @return Error[]
     */
    public function getErrors(): array
    {
        $this->initialize();

        return $this->Errors;
    }

    /**
     * @return RequestCharged::*|null
     */
    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->RequestCharged;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->RequestCharged = $headers['x-amz-request-charged'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent());
        $this->Deleted = !$data->Deleted ? [] : (function (\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml as $item) {
                $items[] = new DeletedObject([
                    'Key' => ($v = $item->Key) ? (string) $v : null,
                    'VersionId' => ($v = $item->VersionId) ? (string) $v : null,
                    'DeleteMarker' => ($v = $item->DeleteMarker) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
                    'DeleteMarkerVersionId' => ($v = $item->DeleteMarkerVersionId) ? (string) $v : null,
                ]);
            }

            return $items;
        })($data->Deleted);
        $this->Errors = !$data->Error ? [] : (function (\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml as $item) {
                $items[] = new Error([
                    'Key' => ($v = $item->Key) ? (string) $v : null,
                    'VersionId' => ($v = $item->VersionId) ? (string) $v : null,
                    'Code' => ($v = $item->Code) ? (string) $v : null,
                    'Message' => ($v = $item->Message) ? (string) $v : null,
                ]);
            }

            return $items;
        })($data->Error);
    }
}
