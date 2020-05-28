<?php

namespace AsyncAws\CloudFront\ValueObject;

final class Invalidation
{
    /**
     * The identifier for the invalidation request. For example: `IDFDVBD632BHDS5`.
     */
    private $Id;

    /**
     * The status of the invalidation request. When the invalidation batch is finished, the status is `Completed`.
     */
    private $Status;

    /**
     * The date and time the invalidation request was first made.
     */
    private $CreateTime;

    /**
     * The current invalidation information for the batch request.
     */
    private $InvalidationBatch;

    /**
     * @param array{
     *   Id: string,
     *   Status: string,
     *   CreateTime: \DateTimeImmutable,
     *   InvalidationBatch: \AsyncAws\CloudFront\ValueObject\InvalidationBatch|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Id = $input['Id'] ?? null;
        $this->Status = $input['Status'] ?? null;
        $this->CreateTime = $input['CreateTime'] ?? null;
        $this->InvalidationBatch = isset($input['InvalidationBatch']) ? InvalidationBatch::create($input['InvalidationBatch']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreateTime(): \DateTimeImmutable
    {
        return $this->CreateTime;
    }

    public function getId(): string
    {
        return $this->Id;
    }

    public function getInvalidationBatch(): InvalidationBatch
    {
        return $this->InvalidationBatch;
    }

    public function getStatus(): string
    {
        return $this->Status;
    }
}
