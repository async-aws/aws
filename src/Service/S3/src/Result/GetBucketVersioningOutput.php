<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\BucketVersioningStatus;
use AsyncAws\S3\Enum\MFADeleteStatus;

class GetBucketVersioningOutput extends Result
{
    /**
     * The versioning state of the bucket.
     */
    private $status;

    /**
     * Specifies whether MFA delete is enabled in the bucket versioning configuration. This element is only returned if the
     * bucket has been configured with MFA delete. If the bucket has never been so configured, this element is not returned.
     */
    private $mfaDelete;

    /**
     * @return MFADeleteStatus::*|null
     */
    public function getMfaDelete(): ?string
    {
        $this->initialize();

        return $this->mfaDelete;
    }

    /**
     * @return BucketVersioningStatus::*|null
     */
    public function getStatus(): ?string
    {
        $this->initialize();

        return $this->status;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $this->status = ($v = $data->Status) ? (string) $v : null;
        $this->mfaDelete = ($v = $data->MfaDelete) ? (string) $v : null;
    }
}
