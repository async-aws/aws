<?php

namespace AsyncAws\Rekognition\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class CreateCollectionResponse extends Result
{
    /**
     * HTTP status code indicating the result of the operation.
     */
    private $StatusCode;

    /**
     * Amazon Resource Name (ARN) of the collection. You can use this to manage permissions on your resources.
     */
    private $CollectionArn;

    /**
     * Version number of the face detection model associated with the collection you are creating.
     */
    private $FaceModelVersion;

    public function getCollectionArn(): ?string
    {
        $this->initialize();

        return $this->CollectionArn;
    }

    public function getFaceModelVersion(): ?string
    {
        $this->initialize();

        return $this->FaceModelVersion;
    }

    public function getStatusCode(): ?int
    {
        $this->initialize();

        return $this->StatusCode;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->StatusCode = isset($data['StatusCode']) ? (int) $data['StatusCode'] : null;
        $this->CollectionArn = isset($data['CollectionArn']) ? (string) $data['CollectionArn'] : null;
        $this->FaceModelVersion = isset($data['FaceModelVersion']) ? (string) $data['FaceModelVersion'] : null;
    }
}
