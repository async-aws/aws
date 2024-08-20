<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Lambda\Enum\Architecture;
use AsyncAws\Lambda\Enum\Runtime;
use AsyncAws\Lambda\ValueObject\LayerVersionContentOutput;

class PublishLayerVersionResponse extends Result
{
    /**
     * Details about the layer version.
     *
     * @var LayerVersionContentOutput|null
     */
    private $content;

    /**
     * The ARN of the layer.
     *
     * @var string|null
     */
    private $layerArn;

    /**
     * The ARN of the layer version.
     *
     * @var string|null
     */
    private $layerVersionArn;

    /**
     * The description of the version.
     *
     * @var string|null
     */
    private $description;

    /**
     * The date that the layer version was created, in ISO-8601 format [^1] (YYYY-MM-DDThh:mm:ss.sTZD).
     *
     * [^1]: https://www.w3.org/TR/NOTE-datetime
     *
     * @var string|null
     */
    private $createdDate;

    /**
     * The version number.
     *
     * @var int|null
     */
    private $version;

    /**
     * The layer's compatible runtimes.
     *
     * The following list includes deprecated runtimes. For more information, see Runtime use after deprecation [^1].
     *
     * For a list of all currently supported runtimes, see Supported runtimes [^2].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/lambda-runtimes.html#runtime-deprecation-levels
     * [^2]: https://docs.aws.amazon.com/lambda/latest/dg/lambda-runtimes.html#runtimes-supported
     *
     * @var list<Runtime::*>
     */
    private $compatibleRuntimes;

    /**
     * The layer's software license.
     *
     * @var string|null
     */
    private $licenseInfo;

    /**
     * A list of compatible instruction set architectures [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/foundation-arch.html
     *
     * @var list<Architecture::*>
     */
    private $compatibleArchitectures;

    /**
     * @return list<Architecture::*>
     */
    public function getCompatibleArchitectures(): array
    {
        $this->initialize();

        return $this->compatibleArchitectures;
    }

    /**
     * @return list<Runtime::*>
     */
    public function getCompatibleRuntimes(): array
    {
        $this->initialize();

        return $this->compatibleRuntimes;
    }

    public function getContent(): ?LayerVersionContentOutput
    {
        $this->initialize();

        return $this->content;
    }

    public function getCreatedDate(): ?string
    {
        $this->initialize();

        return $this->createdDate;
    }

    public function getDescription(): ?string
    {
        $this->initialize();

        return $this->description;
    }

    public function getLayerArn(): ?string
    {
        $this->initialize();

        return $this->layerArn;
    }

    public function getLayerVersionArn(): ?string
    {
        $this->initialize();

        return $this->layerVersionArn;
    }

    public function getLicenseInfo(): ?string
    {
        $this->initialize();

        return $this->licenseInfo;
    }

    public function getVersion(): ?int
    {
        $this->initialize();

        return $this->version;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->content = empty($data['Content']) ? null : $this->populateResultLayerVersionContentOutput($data['Content']);
        $this->layerArn = isset($data['LayerArn']) ? (string) $data['LayerArn'] : null;
        $this->layerVersionArn = isset($data['LayerVersionArn']) ? (string) $data['LayerVersionArn'] : null;
        $this->description = isset($data['Description']) ? (string) $data['Description'] : null;
        $this->createdDate = isset($data['CreatedDate']) ? (string) $data['CreatedDate'] : null;
        $this->version = isset($data['Version']) ? (int) $data['Version'] : null;
        $this->compatibleRuntimes = empty($data['CompatibleRuntimes']) ? [] : $this->populateResultCompatibleRuntimes($data['CompatibleRuntimes']);
        $this->licenseInfo = isset($data['LicenseInfo']) ? (string) $data['LicenseInfo'] : null;
        $this->compatibleArchitectures = empty($data['CompatibleArchitectures']) ? [] : $this->populateResultCompatibleArchitectures($data['CompatibleArchitectures']);
    }

    /**
     * @return list<Architecture::*>
     */
    private function populateResultCompatibleArchitectures(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return list<Runtime::*>
     */
    private function populateResultCompatibleRuntimes(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultLayerVersionContentOutput(array $json): LayerVersionContentOutput
    {
        return new LayerVersionContentOutput([
            'Location' => isset($json['Location']) ? (string) $json['Location'] : null,
            'CodeSha256' => isset($json['CodeSha256']) ? (string) $json['CodeSha256'] : null,
            'CodeSize' => isset($json['CodeSize']) ? (int) $json['CodeSize'] : null,
            'SigningProfileVersionArn' => isset($json['SigningProfileVersionArn']) ? (string) $json['SigningProfileVersionArn'] : null,
            'SigningJobArn' => isset($json['SigningJobArn']) ? (string) $json['SigningJobArn'] : null,
        ]);
    }
}
