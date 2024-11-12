<?php

namespace AsyncAws\S3\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\S3\Enum\IntelligentTieringAccessTier;
use AsyncAws\S3\Enum\StorageClass;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Object is archived and inaccessible until restored.
 *
 * If the object you are retrieving is stored in the S3 Glacier Flexible Retrieval storage class, the S3 Glacier Deep
 * Archive storage class, the S3 Intelligent-Tiering Archive Access tier, or the S3 Intelligent-Tiering Deep Archive
 * Access tier, before you can retrieve the object you must first restore a copy using RestoreObject [^1]. Otherwise,
 * this operation returns an `InvalidObjectState` error. For information about restoring archived objects, see Restoring
 * Archived Objects [^2] in the *Amazon S3 User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_RestoreObject.html
 * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/dev/restoring-objects.html
 */
final class InvalidObjectStateException extends ClientException
{
    /**
     * @var StorageClass::*|null
     */
    private $storageClass;

    /**
     * @var IntelligentTieringAccessTier::*|null
     */
    private $accessTier;

    /**
     * @return IntelligentTieringAccessTier::*|null
     */
    public function getAccessTier(): ?string
    {
        return $this->accessTier;
    }

    /**
     * @return StorageClass::*|null
     */
    public function getStorageClass(): ?string
    {
        return $this->storageClass;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        if (0 < $data->Error->count()) {
            $data = $data->Error;
        }
        $this->storageClass = (null !== $v = $data->StorageClass[0]) ? (string) $v : null;
        $this->accessTier = (null !== $v = $data->AccessTier[0]) ? (string) $v : null;
    }
}
