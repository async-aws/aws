<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\ImageBuilder\Enum\SsmParameterDataType;

/**
 * Configuration for a single Parameter in the Amazon Web Services Systems Manager (SSM) Parameter Store in a given
 * Region.
 */
final class SsmParameterConfiguration
{
    /**
     * Specify the account that will own the Parameter in a given Region. During distribution, this account must be
     * specified in distribution settings as a target account for the Region.
     *
     * @var string|null
     */
    private $amiAccountId;

    /**
     * This is the name of the Parameter in the target Region or account. The image distribution creates the Parameter if it
     * doesn't already exist. Otherwise, it updates the parameter.
     *
     * @var string
     */
    private $parameterName;

    /**
     * The data type specifies what type of value the Parameter contains. We recommend that you use data type
     * `aws:ec2:image`.
     *
     * @var SsmParameterDataType::*|null
     */
    private $dataType;

    /**
     * @param array{
     *   amiAccountId?: string|null,
     *   parameterName: string,
     *   dataType?: SsmParameterDataType::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->amiAccountId = $input['amiAccountId'] ?? null;
        $this->parameterName = $input['parameterName'] ?? $this->throwException(new InvalidArgument('Missing required field "parameterName".'));
        $this->dataType = $input['dataType'] ?? null;
    }

    /**
     * @param array{
     *   amiAccountId?: string|null,
     *   parameterName: string,
     *   dataType?: SsmParameterDataType::*|null,
     * }|SsmParameterConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAmiAccountId(): ?string
    {
        return $this->amiAccountId;
    }

    /**
     * @return SsmParameterDataType::*|null
     */
    public function getDataType(): ?string
    {
        return $this->dataType;
    }

    public function getParameterName(): string
    {
        return $this->parameterName;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
