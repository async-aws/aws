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

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $headers = $response->getHeaders(false);

        $this->RequestCharged = $headers['x-amz-request-charged'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent(false));
        $this->Deleted = !$data->Deleted ? [] : (function (\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml as $item) {
                $items[] = new DeletedObject([
                    'Key' => ($v = $item->Key) ? (string) $v : null,
                    'VersionId' => ($v = $item->VersionId) ? (string) $v : null,
                    'DeleteMarker' => ($v = $item->DeleteMarker) ? 'true' === (string) $v : null,
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
