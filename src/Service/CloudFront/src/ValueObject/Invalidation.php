<?php

namespace AsyncAws\CloudFront\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * An invalidation.
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
        $this->id = $input['Id'] ?? $this->throwException(new InvalidArgument('Missing required field "Id".'));
        $this->status = $input['Status'] ?? $this->throwException(new InvalidArgument('Missing required field "Status".'));
        $this->createTime = $input['CreateTime'] ?? $this->throwException(new InvalidArgument('Missing required field "CreateTime".'));
        $this->invalidationBatch = isset($input['InvalidationBatch']) ? InvalidationBatch::create($input['InvalidationBatch']) : $this->throwException(new InvalidArgument('Missing required field "InvalidationBatch".'));
    }

    /**
     * @param array{
     *   Id: string,
     *   Status: string,
     *   CreateTime: \DateTimeImmutable,
     *   InvalidationBatch: InvalidationBatch|array,
     * }|Invalidation $input
     */
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
