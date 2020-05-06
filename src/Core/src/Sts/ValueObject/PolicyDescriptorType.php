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
    private $Arn;

    /**
     * @param array{
     *   Arn?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        if (isset($input['arn'])) {
            @trigger_error(sprintf('Using key arn in "%s" is deprecated. Use Arn instead.', __CLASS__), \E_USER_DEPRECATED);
            $input['Arn'] = $input['arn'];
        }
        $this->Arn = $input['Arn'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): ?string
    {
        return $this->Arn;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->Arn) {
            $payload['arn'] = $v;
        }

        return $payload;
    }
}
