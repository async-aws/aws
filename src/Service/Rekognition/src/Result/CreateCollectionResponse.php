<?php

namespace AsyncAws\Rekognition\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class CreateCollectionResponse extends Result
{
    /**
     * HTTP status code indicating the result of the operation.
     */
    private $statusCode;

    /**
     * Amazon Resource Name (ARN) of the collection. You can use this to manage permissions on your resources.
     */
    private $collectionArn;

    /**
     * Latest face model being used with the collection. For more information, see Model versioning.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/face-detection-model.html
     */
    private $faceModelVersion;

    public function getCollectionArn(): ?string
    {
        $this->initialize();

        return $this->collectionArn;
    }

    public function getFaceModelVersion(): ?string
    {
        $this->initialize();

        return $this->faceModelVersion;
    }

    public function getStatusCode(): ?int
    {
        $this->initialize();

        return $this->statusCode;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->statusCode = isset($data['StatusCode']) ? (int) $data['StatusCode'] : null;
        $this->collectionArn = isset($data['CollectionArn']) ? (string) $data['CollectionArn'] : null;
        $this->faceModelVersion = isset($data['FaceModelVersion']) ? (string) $data['FaceModelVersion'] : null;
    }
}
