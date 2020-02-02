<?php

namespace AsyncAws\Core\Sts\Result;

class AssumedRoleUser
{
    private $AssumedRoleId;

    private $Arn;

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @param array{
     *   AssumedRoleId: string,
     *   Arn: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->AssumedRoleId = $input['AssumedRoleId'] ?? null;
        $this->Arn = $input['Arn'] ?? null;
    }

    public function getAssumedRoleId(): string
    {
        return $this->AssumedRoleId;
    }

    public function getArn(): string
    {
        return $this->Arn;
    }
}
