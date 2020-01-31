<?php

namespace AsyncAws\S3\Result;

class Owner
{
    private $DisplayName;

    private $ID;

    public function getDisplayName(): ?string
    {
        return $this->DisplayName;
    }

    public function getID(): ?string
    {
        return $this->ID;
    }
}
