<?php

namespace AsyncAws\Core\Sts\Input;

class PolicyDescriptorType
{
    /**
     * The Amazon Resource Name (ARN) of the IAM managed policy to use as a session policy for the role. For more
     * information about ARNs, see Amazon Resource Names (ARNs) and AWS Service Namespaces in the *AWS General Reference*.
     *
     * @see https://docs.aws.amazon.com/general/latest/gr/aws-arns-and-namespaces.html
     *
     * @var string|null
     */
    private $arn;

    /**
     * @param array{
     *   arn?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->arn = $input['arn'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getarn(): ?string
    {
        return $this->arn;
    }

    public function setarn(?string $value): self
    {
        $this->arn = $value;

        return $this;
    }

    public function validate(): void
    {
        // There are no required properties
    }
}
