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
     *
     * @var BucketVersioningStatus::*|null
     */
    private $status;

    /**
     * Specifies whether MFA delete is enabled in the bucket versioning configuration. This element is only returned if the
     * bucket has been configured with MFA delete. If the bucket has never been so configured, this element is not returned.
     *
     * @var MFADeleteStatus::*|null
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
        $this->status = (null !== $v = $data->Status[0]) ? (!BucketVersioningStatus::exists((string) $data->Status) ? BucketVersioningStatus::UNKNOWN_TO_SDK : (string) $data->Status) : null;
        $this->mfaDelete = (null !== $v = $data->MfaDelete[0]) ? (!MFADeleteStatus::exists((string) $data->MfaDelete) ? MFADeleteStatus::UNKNOWN_TO_SDK : (string) $data->MfaDelete) : null;
    }
}
