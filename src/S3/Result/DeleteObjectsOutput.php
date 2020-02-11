<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

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

    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->RequestCharged;
    }

    protected function populateResult(ResponseInterface $response, ?HttpClientInterface $httpClient): void
    {
        $headers = $response->getHeaders(false);

        $this->RequestCharged = $headers['x-amz-request-charged'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent(false));
        $this->Deleted = (static function (\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml as $item) {
                $items[] = new DeletedObject([
                    'Key' => static::xmlValueOrNull($item->Key, 'string'),
                    'VersionId' => static::xmlValueOrNull($item->VersionId, 'string'),
                    'DeleteMarker' => static::xmlValueOrNull($item->DeleteMarker, 'bool'),
                    'DeleteMarkerVersionId' => static::xmlValueOrNull($item->DeleteMarkerVersionId, 'string'),
                ]);
            }

            return $items;
        })($data->Deleted);
        $this->Errors = (static function (\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml as $item) {
                $items[] = new Error([
                    'Key' => static::xmlValueOrNull($item->Key, 'string'),
                    'VersionId' => static::xmlValueOrNull($item->VersionId, 'string'),
                    'Code' => static::xmlValueOrNull($item->Code, 'string'),
                    'Message' => static::xmlValueOrNull($item->Message, 'string'),
                ]);
            }

            return $items;
        })($data->Error);
    }
}
