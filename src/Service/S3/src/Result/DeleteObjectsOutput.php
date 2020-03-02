<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\RequestCharged;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class DeleteObjectsOutput extends Result
{
    private $Deleted = [];

    private $RequestCharged;

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
     * @return RequestCharged::REQUESTER|null
     */
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
