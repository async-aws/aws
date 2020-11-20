<?php

namespace AsyncAws\S3\Enum;

final class Event
{
    public const S_3_OBJECT_CREATED_ = 's3:ObjectCreated:*';
    public const S_3_OBJECT_CREATED_COMPLETE_MULTIPART_UPLOAD = 's3:ObjectCreated:CompleteMultipartUpload';
    public const S_3_OBJECT_CREATED_COPY = 's3:ObjectCreated:Copy';
    public const S_3_OBJECT_CREATED_POST = 's3:ObjectCreated:Post';
    public const S_3_OBJECT_CREATED_PUT = 's3:ObjectCreated:Put';
    public const S_3_OBJECT_REMOVED_ = 's3:ObjectRemoved:*';
    public const S_3_OBJECT_REMOVED_DELETE = 's3:ObjectRemoved:Delete';
    public const S_3_OBJECT_REMOVED_DELETE_MARKER_CREATED = 's3:ObjectRemoved:DeleteMarkerCreated';
    public const S_3_OBJECT_RESTORE_ = 's3:ObjectRestore:*';
    public const S_3_OBJECT_RESTORE_COMPLETED = 's3:ObjectRestore:Completed';
    public const S_3_OBJECT_RESTORE_POST = 's3:ObjectRestore:Post';
    public const S_3_REDUCED_REDUNDANCY_LOST_OBJECT = 's3:ReducedRedundancyLostObject';
    public const S_3_REPLICATION_ = 's3:Replication:*';
    public const S_3_REPLICATION_OPERATION_FAILED_REPLICATION = 's3:Replication:OperationFailedReplication';
    public const S_3_REPLICATION_OPERATION_MISSED_THRESHOLD = 's3:Replication:OperationMissedThreshold';
    public const S_3_REPLICATION_OPERATION_NOT_TRACKED = 's3:Replication:OperationNotTracked';
    public const S_3_REPLICATION_OPERATION_REPLICATED_AFTER_THRESHOLD = 's3:Replication:OperationReplicatedAfterThreshold';

    public static function exists(string $value): bool
    {
        return isset([
            self::S_3_OBJECT_CREATED_ => true,
            self::S_3_OBJECT_CREATED_COMPLETE_MULTIPART_UPLOAD => true,
            self::S_3_OBJECT_CREATED_COPY => true,
            self::S_3_OBJECT_CREATED_POST => true,
            self::S_3_OBJECT_CREATED_PUT => true,
            self::S_3_OBJECT_REMOVED_ => true,
            self::S_3_OBJECT_REMOVED_DELETE => true,
            self::S_3_OBJECT_REMOVED_DELETE_MARKER_CREATED => true,
            self::S_3_OBJECT_RESTORE_ => true,
            self::S_3_OBJECT_RESTORE_COMPLETED => true,
            self::S_3_OBJECT_RESTORE_POST => true,
            self::S_3_REDUCED_REDUNDANCY_LOST_OBJECT => true,
            self::S_3_REPLICATION_ => true,
            self::S_3_REPLICATION_OPERATION_FAILED_REPLICATION => true,
            self::S_3_REPLICATION_OPERATION_MISSED_THRESHOLD => true,
            self::S_3_REPLICATION_OPERATION_NOT_TRACKED => true,
            self::S_3_REPLICATION_OPERATION_REPLICATED_AFTER_THRESHOLD => true,
        ][$value]);
    }
}
