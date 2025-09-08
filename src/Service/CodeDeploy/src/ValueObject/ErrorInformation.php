<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\ErrorCode;

/**
 * Information about a deployment error.
 */
final class ErrorInformation
{
    /**
     * For more information, see Error Codes for CodeDeploy [^1] in the CodeDeploy User Guide [^2].
     *
     * The error code:
     *
     * - APPLICATION_MISSING: The application was missing. This error code is most likely raised if the application is
     *   deleted after the deployment is created, but before it is started.
     * - DEPLOYMENT_GROUP_MISSING: The deployment group was missing. This error code is most likely raised if the deployment
     *   group is deleted after the deployment is created, but before it is started.
     * - HEALTH_CONSTRAINTS: The deployment failed on too many instances to be successfully deployed within the instance
     *   health constraints specified.
     * - HEALTH_CONSTRAINTS_INVALID: The revision cannot be successfully deployed within the instance health constraints
     *   specified.
     * - IAM_ROLE_MISSING: The service role cannot be accessed.
     * - IAM_ROLE_PERMISSIONS: The service role does not have the correct permissions.
     * - INTERNAL_ERROR: There was an internal error.
     * - NO_EC2_SUBSCRIPTION: The calling account is not subscribed to Amazon EC2.
     * - NO_INSTANCES: No instances were specified, or no instances can be found.
     * - OVER_MAX_INSTANCES: The maximum number of instances was exceeded.
     * - THROTTLED: The operation was throttled because the calling account exceeded the throttling limits of one or more
     *   Amazon Web Services services.
     * - TIMEOUT: The deployment has timed out.
     * - REVISION_MISSING: The revision ID was missing. This error code is most likely raised if the revision is deleted
     *   after the deployment is created, but before it is started.
     *
     * [^1]: https://docs.aws.amazon.com/codedeploy/latest/userguide/error-codes.html
     * [^2]: https://docs.aws.amazon.com/codedeploy/latest/userguide
     *
     * @var ErrorCode::*|null
     */
    private $code;

    /**
     * An accompanying error message.
     *
     * @var string|null
     */
    private $message;

    /**
     * @param array{
     *   code?: ErrorCode::*|null,
     *   message?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->code = $input['code'] ?? null;
        $this->message = $input['message'] ?? null;
    }

    /**
     * @param array{
     *   code?: ErrorCode::*|null,
     *   message?: string|null,
     * }|ErrorInformation $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ErrorCode::*|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
