<?php

namespace AsyncAws\Iam\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class PutUserPolicyRequest extends Input
{
    /**
     * The name of the user to associate the policy with.
     *
     * This parameter allows (through its regex pattern [^1]) a string of characters consisting of upper and lowercase
     * alphanumeric characters with no spaces. You can also include any of the following characters: _+=,.@-
     *
     * [^1]: http://wikipedia.org/wiki/regex
     *
     * @required
     *
     * @var string|null
     */
    private $userName;

    /**
     * The name of the policy document.
     *
     * This parameter allows (through its regex pattern [^1]) a string of characters consisting of upper and lowercase
     * alphanumeric characters with no spaces. You can also include any of the following characters: _+=,.@-
     *
     * [^1]: http://wikipedia.org/wiki/regex
     *
     * @required
     *
     * @var string|null
     */
    private $policyName;

    /**
     * The policy document.
     *
     * You must provide policies in JSON format in IAM. However, for CloudFormation templates formatted in YAML, you can
     * provide the policy in JSON or YAML format. CloudFormation always converts a YAML policy to JSON format before
     * submitting it to IAM.
     *
     * The regex pattern [^1] used to validate this parameter is a string of characters consisting of the following:
     *
     * - Any printable ASCII character ranging from the space character (`\u0020`) through the end of the ASCII character
     *   range
     * - The printable characters in the Basic Latin and Latin-1 Supplement character set (through `\u00FF`)
     * - The special characters tab (`\u0009`), line feed (`\u000A`), and carriage return (`\u000D`)
     *
     * [^1]: http://wikipedia.org/wiki/regex
     *
     * @required
     *
     * @var string|null
     */
    private $policyDocument;

    /**
     * @param array{
     *   UserName?: string,
     *   PolicyName?: string,
     *   PolicyDocument?: string,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userName = $input['UserName'] ?? null;
        $this->policyName = $input['PolicyName'] ?? null;
        $this->policyDocument = $input['PolicyDocument'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPolicyDocument(): ?string
    {
        return $this->policyDocument;
    }

    public function getPolicyName(): ?string
    {
        return $this->policyName;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = http_build_query(['Action' => 'PutUserPolicy', 'Version' => '2010-05-08'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setPolicyDocument(?string $value): self
    {
        $this->policyDocument = $value;

        return $this;
    }

    public function setPolicyName(?string $value): self
    {
        $this->policyName = $value;

        return $this;
    }

    public function setUserName(?string $value): self
    {
        $this->userName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->userName) {
            throw new InvalidArgument(sprintf('Missing parameter "UserName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserName'] = $v;
        if (null === $v = $this->policyName) {
            throw new InvalidArgument(sprintf('Missing parameter "PolicyName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['PolicyName'] = $v;
        if (null === $v = $this->policyDocument) {
            throw new InvalidArgument(sprintf('Missing parameter "PolicyDocument" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['PolicyDocument'] = $v;

        return $payload;
    }
}
