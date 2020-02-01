<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;

class ListObjectsOutput extends Result
{
    use ListObjectsOutputTrait;

    private $IsTruncated;

    private $Marker;

    private $NextMarker;

    private $Contents;

    private $Name;

    private $Prefix;

    private $Delimiter;

    private $MaxKeys;

    private $CommonPrefixes;

    private $EncodingType;

    public function getIsTruncated(): ?bool
    {
        $this->resolve();

        return $this->IsTruncated;
    }

    public function getMarker(): ?string
    {
        $this->resolve();

        return $this->Marker;
    }

    public function getNextMarker(): ?string
    {
        $this->resolve();

        return $this->NextMarker;
    }

    public function getContents(): ?array
    {
        $this->resolve();

        return $this->Contents;
    }

    public function getName(): ?string
    {
        $this->resolve();

        return $this->Name;
    }

    public function getPrefix(): ?string
    {
        $this->resolve();

        return $this->Prefix;
    }

    public function getDelimiter(): ?string
    {
        $this->resolve();

        return $this->Delimiter;
    }

    public function getMaxKeys(): ?int
    {
        $this->resolve();

        return $this->MaxKeys;
    }

    public function getCommonPrefixes(): ?array
    {
        $this->resolve();

        return $this->CommonPrefixes;
    }

    public function getEncodingType(): ?string
    {
        $this->resolve();

        return $this->EncodingType;
    }
}
