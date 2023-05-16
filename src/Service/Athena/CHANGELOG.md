# Change Log

## NOT RELEASED

### Added

- Added `us-iso-east-1` region
- AWS api-change: You can now define custom spark properties at start of the session for use cases like cluster encryption, table formats, and general Spark tuning.
- AWS api-change: You can now use capacity reservations on Amazon Athena to run SQL queries on fully-managed compute capacity.
- AWS api-change: Make DefaultExecutorDpuSize and CoordinatorDpuSize fields optional in StartSession
- AWS api-change: Enforces a minimal level of encryption for the workgroup for query and calculation results that are written to Amazon S3. When enabled, workgroup users can set encryption only to the minimum level set by the administrator or higher when they submit queries.
- AWS api-change: A new field SubstatementType is added to GetQueryExecution API, so customers have an error free way to detect the query type and interpret the result.
- AWS api-change: Add missed InvalidRequestException in GetCalculationExecutionCode,StopCalculationExecution APIs. Correct required parameters (Payload and Type) in UpdateNotebook API. Change Notebook size from 15 Mb to 10 Mb.
- AWS api-change: This release includes support for using Apache Spark in Amazon Athena.
- AWS api-change: Adds support for using Query Result Reuse
- AWS api-change: This feature allows customers to retrieve runtime statistics for completed queries
- AWS api-change: This release updates data types that contain either QueryExecutionId, NamedQueryId or ExpectedBucketOwner. Ids must be between 1 and 128 characters and contain only non-whitespace characters. ExpectedBucketOwner must be 12-digit string.
- AWS api-change: This feature introduces the API support for Athena's parameterized query and BatchGetPreparedStatement API.
- AWS api-change: This release adds subfields, ErrorMessage, Retryable, to the AthenaError response object in the GetQueryExecution API when a query fails.
- AWS api-change: This release adds support for S3 Object Ownership by allowing the S3 bucket owner full control canned ACL to be set when Athena writes query results to S3 buckets.
- AWS api-change: This release adds support for updating an existing named query.
- AWS api-change: This release adds a subfield, ErrorType, to the AthenaError response object in the GetQueryExecution API when a query fails.
- AWS api-change: You can now optionally specify the account ID that you expect to be the owner of your query results output location bucket in Athena. If the account ID of the query results bucket owner does not match the specified account ID, attempts to output to the bucket will fail with an S3 permissions error.
- AWS api-change: This release adds a field, AthenaError, to the GetQueryExecution response object when a query fails.

## 0.1.0

First version
