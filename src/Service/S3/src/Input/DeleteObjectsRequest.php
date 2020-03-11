<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\RequestPayer;

class DeleteObjectsRequest
{
    /**
     * The bucket name containing the objects to delete.
     *
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * Container for the request.
     *
     * @required
     *
     * @var Delete|null
     */
    private $Delete;

    /**
     * The concatenation of the authentication device's serial number, a space, and the value that is displayed on your
     * authentication device. Required to permanently delete a versioned object if versioning is configured with MFA delete
     * enabled.
     *
     * @var string|null
     */
    private $MFA;

    /**
     * @var RequestPayer::*|null
     */
    private $RequestPayer;

    /**
     * Specifies whether you want to delete this object even if it has a Governance-type Object Lock in place. You must have
     * sufficient permissions to perform this operation.
     *
     * @var bool|null
     */
    private $BypassGovernanceRetention;

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/multiobjectdeleteapi.html
     *
     * @param array{
     *   Bucket?: string,
     *   Delete?: \AsyncAws\S3\Input\Delete|array,
     *   MFA?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     *   BypassGovernanceRetention?: bool,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Bucket = $input['Bucket'] ?? null;
        $this->Delete = isset($input['Delete']) ? Delete::create($input['Delete']) : null;
        $this->MFA = $input['MFA'] ?? null;
        $this->RequestPayer = $input['RequestPayer'] ?? null;
        $this->BypassGovernanceRetention = $input['BypassGovernanceRetention'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->Bucket;
    }

    public function getBypassGovernanceRetention(): ?bool
    {
        return $this->BypassGovernanceRetention;
    }

    public function getDelete(): ?Delete
    {
        return $this->Delete;
    }

    public function getMFA(): ?string
    {
        return $this->MFA;
    }

    /**
     * @return RequestPayer::*|null
     */
    public function getRequestPayer(): ?string
    {
        return $this->RequestPayer;
    }

    /**
     * @internal
     */
    public function requestBody(): string
    {
        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = false;

        if (null === $input = $this->Delete) {
            throw new InvalidArgument(sprintf('Missing parameter "Delete" in "%s". The value cannot be null.', __CLASS__));
        }

        $document->appendChild($document_Delete = $document->createElement('Delete'));
        $document_Delete->setAttribute('xmlns', 'http://s3.amazonaws.com/doc/2006-03-01/');

        $input_Objects = $input->getObjects();

        foreach ($input_Objects as $input_ObjectsItem) {
            $document_Delete->appendChild($document_Delete_Objects = $document->createElement('Object'));

            $input_ObjectsItem_Key = $input_ObjectsItem->getKey();
            $document_Delete_Objects->appendChild($document->createElement('Key', $input_ObjectsItem_Key));

            if (null !== $input_ObjectsItem_VersionId = $input_ObjectsItem->getVersionId()) {
                $document_Delete_Objects->appendChild($document->createElement('VersionId', $input_ObjectsItem_VersionId));
            }
        }

        if (null !== $input_Quiet = $input->getQuiet()) {
            $document_Delete->appendChild($document->createElement('Quiet', $input_Quiet ? 'true' : 'false'));
        }

        return $document->hasChildNodes() ? $document->saveXML() : '';
    }

    /**
     * @internal
     */
    public function requestHeaders(): array
    {
        $headers = ['content-type' => 'application/xml'];
        if (null !== $this->MFA) {
            $headers['x-amz-mfa'] = $this->MFA;
        }
        if (null !== $this->RequestPayer) {
            $headers['x-amz-request-payer'] = $this->RequestPayer;
        }
        if (null !== $this->BypassGovernanceRetention) {
            $headers['x-amz-bypass-governance-retention'] = $this->BypassGovernanceRetention;
        }

        return $headers;
    }

    /**
     * @internal
     */
    public function requestQuery(): array
    {
        $query = [];

        return $query;
    }

    /**
     * @internal
     */
    public function requestUri(): string
    {
        $uri = [];
        $uri['Bucket'] = $this->Bucket ?? '';

        return "/{$uri['Bucket']}?delete";
    }

    public function setBucket(?string $value): self
    {
        $this->Bucket = $value;

        return $this;
    }

    public function setBypassGovernanceRetention(?bool $value): self
    {
        $this->BypassGovernanceRetention = $value;

        return $this;
    }

    public function setDelete(?Delete $value): self
    {
        $this->Delete = $value;

        return $this;
    }

    public function setMFA(?string $value): self
    {
        $this->MFA = $value;

        return $this;
    }

    /**
     * @param RequestPayer::*|null $value
     */
    public function setRequestPayer(?string $value): self
    {
        $this->RequestPayer = $value;

        return $this;
    }

    public function validate(): void
    {
        if (null === $this->Bucket) {
            throw new InvalidArgument(sprintf('Missing parameter "Bucket" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->Delete) {
            throw new InvalidArgument(sprintf('Missing parameter "Delete" when validating the "%s". The value cannot be null.', __CLASS__));
        }
        $this->Delete->validate();

        if (null !== $this->RequestPayer) {
            if (!RequestPayer::exists($this->RequestPayer)) {
                throw new InvalidArgument(sprintf('Invalid parameter "RequestPayer" when validating the "%s". The value "%s" is not a valid "RequestPayer".', __CLASS__, $this->RequestPayer));
            }
        }
    }
}
