<?php

namespace AsyncAws\CloudFront\ValueObject;

/**
 * The invalidation's information.
 */
final class Invalidation
{
    /**
     * The identifier for the invalidation request. For example: `IDFDVBD632BHDS5`.
     */
    private $id;

    /**
     * The status of the invalidation request. When the invalidation batch is finished, the status is `Completed`.
     */
    private $status;

    /**
     * The date and time the invalidation request was first made.
     */
    private $createTime;

    /**
     * The current invalidation information for the batch request.
     */
    private $invalidationBatch;

    /**
     * @param array{
     *   Id: string,
     *   Status: string,
     *   CreateTime: \DateTimeImmutable,
     *   InvalidationBatch: InvalidationBatch|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? null;
        $this->status = $input['Status'] ?? null;
        $this->createTime = $input['CreateTime'] ?? null;
        $this->invalidationBatch = isset($input['InvalidationBatch']) ? InvalidationBatch::create($input['InvalidationBatch']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreateTime(): \DateTimeImmutable
    {
        return $this->createTime;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getInvalidationBatch(): InvalidationBatch
    {
        return $this->invalidationBatch;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
