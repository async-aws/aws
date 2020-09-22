<?php

namespace AsyncAws\Core\Sts\ValueObject;

final class PolicyDescriptorType
{
    /**
     * The Amazon Resource Name (ARN) of the IAM managed policy to use as a session policy for the role. For more
     * information about ARNs, see Amazon Resource Names (ARNs) and AWS Service Namespaces in the *AWS General Reference*.
     *
     * @see https://docs.aws.amazon.com/general/latest/gr/aws-arns-and-namespaces.html
     */
    private $arn;

    /**
     * @param array{
     *   arn?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['arn'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->arn) {
            $payload['arn'] = $v;
        }

        return $payload;
    }
}
