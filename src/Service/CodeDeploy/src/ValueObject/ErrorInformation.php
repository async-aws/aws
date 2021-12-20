<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\ErrorCode;

/**
 * Information about any error associated with this deployment.
 */
final class ErrorInformation
{
    /**
     * For more information, see Error Codes for AWS CodeDeploy in the AWS CodeDeploy User Guide.
     *
     * @see https://docs.aws.amazon.com/codedeploy/latest/userguide/error-codes.html
     * @see https://docs.aws.amazon.com/codedeploy/latest/userguide
     */
    private $code;

    /**
     * An accompanying error message.
     */
    private $message;

    /**
     * @param array{
     *   code?: null|ErrorCode::*,
     *   message?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->code = $input['code'] ?? null;
        $this->message = $input['message'] ?? null;
    }

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
