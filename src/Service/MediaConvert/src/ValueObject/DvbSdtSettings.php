<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\OutputSdt;

/**
 * Use these settings to insert a DVB Service Description Table (SDT) in the transport stream of this output. When you
 * work directly in your JSON job specification, include this object only when your job has a transport stream output
 * and the container settings contain the object M2tsSettings.
 */
final class DvbSdtSettings
{
    /**
     * Selects method of inserting SDT information into output stream. "Follow input SDT" copies SDT information from input
     * stream to output stream. "Follow input SDT if present" copies SDT information from input stream to output stream if
     * SDT information is present in the input, otherwise it will fall back on the user-defined values. Enter "SDT Manually"
     * means user will enter the SDT information. "No SDT" means output stream will not contain SDT information.
     */
    private $outputSdt;

    /**
     * The number of milliseconds between instances of this table in the output transport stream.
     */
    private $sdtInterval;

    /**
     * The service name placed in the service_descriptor in the Service Description Table. Maximum length is 256 characters.
     */
    private $serviceName;

    /**
     * The service provider name placed in the service_descriptor in the Service Description Table. Maximum length is 256
     * characters.
     */
    private $serviceProviderName;

    /**
     * @param array{
     *   OutputSdt?: null|OutputSdt::*,
     *   SdtInterval?: null|int,
     *   ServiceName?: null|string,
     *   ServiceProviderName?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->outputSdt = $input['OutputSdt'] ?? null;
        $this->sdtInterval = $input['SdtInterval'] ?? null;
        $this->serviceName = $input['ServiceName'] ?? null;
        $this->serviceProviderName = $input['ServiceProviderName'] ?? null;
    }

    /**
     * @param array{
     *   OutputSdt?: null|OutputSdt::*,
     *   SdtInterval?: null|int,
     *   ServiceName?: null|string,
     *   ServiceProviderName?: null|string,
     * }|DvbSdtSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return OutputSdt::*|null
     */
    public function getOutputSdt(): ?string
    {
        return $this->outputSdt;
    }

    public function getSdtInterval(): ?int
    {
        return $this->sdtInterval;
    }

    public function getServiceName(): ?string
    {
        return $this->serviceName;
    }

    public function getServiceProviderName(): ?string
    {
        return $this->serviceProviderName;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->outputSdt) {
            if (!OutputSdt::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "outputSdt" for "%s". The value "%s" is not a valid "OutputSdt".', __CLASS__, $v));
            }
            $payload['outputSdt'] = $v;
        }
        if (null !== $v = $this->sdtInterval) {
            $payload['sdtInterval'] = $v;
        }
        if (null !== $v = $this->serviceName) {
            $payload['serviceName'] = $v;
        }
        if (null !== $v = $this->serviceProviderName) {
            $payload['serviceProviderName'] = $v;
        }

        return $payload;
    }
}
