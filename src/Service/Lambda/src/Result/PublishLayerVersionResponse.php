<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Lambda\Enum\Runtime;
use AsyncAws\Lambda\ValueObject\LayerVersionContentOutput;

class PublishLayerVersionResponse extends Result
{
    /**
     * Details about the layer version.
     */
    private $content;

    /**
     * The ARN of the layer.
     */
    private $layerArn;

    /**
     * The ARN of the layer version.
     */
    private $layerVersionArn;

    /**
     * The description of the version.
     */
    private $description;

    /**
     * The date that the layer version was created, in ISO-8601 format (YYYY-MM-DDThh:mm:ss.sTZD).
     *
     * @see https://www.w3.org/TR/NOTE-datetime
     */
    private $createdDate;

    /**
     * The version number.
     */
    private $version;

    /**
     * The layer's compatible runtimes.
     */
    private $compatibleRuntimes = [];

    /**
     * The layer's software license.
     */
    private $licenseInfo;

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

    public function getVersion(): ?string
    {
        $this->initialize();

        return $this->version;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->content = empty($data['Content']) ? null : new LayerVersionContentOutput([
            'Location' => isset($data['Content']['Location']) ? (string) $data['Content']['Location'] : null,
            'CodeSha256' => isset($data['Content']['CodeSha256']) ? (string) $data['Content']['CodeSha256'] : null,
            'CodeSize' => isset($data['Content']['CodeSize']) ? (string) $data['Content']['CodeSize'] : null,
            'SigningProfileVersionArn' => isset($data['Content']['SigningProfileVersionArn']) ? (string) $data['Content']['SigningProfileVersionArn'] : null,
            'SigningJobArn' => isset($data['Content']['SigningJobArn']) ? (string) $data['Content']['SigningJobArn'] : null,
        ]);
        $this->layerArn = isset($data['LayerArn']) ? (string) $data['LayerArn'] : null;
        $this->layerVersionArn = isset($data['LayerVersionArn']) ? (string) $data['LayerVersionArn'] : null;
        $this->description = isset($data['Description']) ? (string) $data['Description'] : null;
        $this->createdDate = isset($data['CreatedDate']) ? (string) $data['CreatedDate'] : null;
        $this->version = isset($data['Version']) ? (string) $data['Version'] : null;
        $this->compatibleRuntimes = empty($data['CompatibleRuntimes']) ? [] : $this->populateResultCompatibleRuntimes($data['CompatibleRuntimes']);
        $this->licenseInfo = isset($data['LicenseInfo']) ? (string) $data['LicenseInfo'] : null;
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
}
