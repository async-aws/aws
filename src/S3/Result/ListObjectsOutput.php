<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;

class ListObjectsOutput extends Result
{
    use ListObjectsOutputTrait;

    private $IsTruncated;

    private $Marker;

    private $NextMarker;

    private $Contents = [];

    private $Name;

    private $Prefix;

    private $Delimiter;

    private $MaxKeys;

    private $CommonPrefixes = [];

    private $EncodingType;

    public function getIsTruncated(): ?bool
    {
        $this->initialize();

        return $this->IsTruncated;
    }

    public function getMarker(): ?string
    {
        $this->initialize();

        return $this->Marker;
    }

    public function getNextMarker(): ?string
    {
        $this->initialize();

        return $this->NextMarker;
    }

    /**
     * @return AwsObject[]
     */
    public function getContents(): array
    {
        $this->initialize();

        return $this->Contents;
    }

    public function getName(): ?string
    {
        $this->initialize();

        return $this->Name;
    }

    public function getPrefix(): ?string
    {
        $this->initialize();

        return $this->Prefix;
    }

    public function getDelimiter(): ?string
    {
        $this->initialize();

        return $this->Delimiter;
    }

    public function getMaxKeys(): ?int
    {
        $this->initialize();

        return $this->MaxKeys;
    }

    /**
     * @return CommonPrefix[]
     */
    public function getCommonPrefixes(): array
    {
        $this->initialize();

        return $this->CommonPrefixes;
    }

    public function getEncodingType(): ?string
    {
        $this->initialize();

        return $this->EncodingType;
    }
}
