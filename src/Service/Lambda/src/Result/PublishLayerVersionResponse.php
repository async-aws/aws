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
    private $Content;

    /**
     * The ARN of the layer.
     */
    private $LayerArn;

    /**
     * The ARN of the layer version.
     */
    private $LayerVersionArn;

    /**
     * The description of the version.
     */
    private $Description;

    /**
     * The date that the layer version was created, in ISO-8601 format (YYYY-MM-DDThh:mm:ss.sTZD).
     *
     * @see https://www.w3.org/TR/NOTE-datetime
     */
    private $CreatedDate;

    /**
     * The version number.
     */
    private $Version;

    /**
     * The layer's compatible runtimes.
     */
    private $CompatibleRuntimes = [];

    /**
     * The layer's software license.
     */
    private $LicenseInfo;

    /**
     * @return list<Runtime::*>
     */
    public function getCompatibleRuntimes(): array
    {
        $this->initialize();

        return $this->CompatibleRuntimes;
    }

    public function getContent(): ?LayerVersionContentOutput
    {
        $this->initialize();

        return $this->Content;
    }

    public function getCreatedDate(): ?string
    {
        $this->initialize();

        return $this->CreatedDate;
    }

    public function getDescription(): ?string
    {
        $this->initialize();

        return $this->Description;
    }

    public function getLayerArn(): ?string
    {
        $this->initialize();

        return $this->LayerArn;
    }

    public function getLayerVersionArn(): ?string
    {
        $this->initialize();

        return $this->LayerVersionArn;
    }

    public function getLicenseInfo(): ?string
    {
        $this->initialize();

        return $this->LicenseInfo;
    }

    public function getVersion(): ?string
    {
        $this->initialize();

        return $this->Version;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->Content = empty($data['Content']) ? null : new LayerVersionContentOutput([
            'Location' => isset($data['Content']['Location']) ? (string) $data['Content']['Location'] : null,
            'CodeSha256' => isset($data['Content']['CodeSha256']) ? (string) $data['Content']['CodeSha256'] : null,
            'CodeSize' => isset($data['Content']['CodeSize']) ? (string) $data['Content']['CodeSize'] : null,
        ]);
        $this->LayerArn = isset($data['LayerArn']) ? (string) $data['LayerArn'] : null;
        $this->LayerVersionArn = isset($data['LayerVersionArn']) ? (string) $data['LayerVersionArn'] : null;
        $this->Description = isset($data['Description']) ? (string) $data['Description'] : null;
        $this->CreatedDate = isset($data['CreatedDate']) ? (string) $data['CreatedDate'] : null;
        $this->Version = isset($data['Version']) ? (string) $data['Version'] : null;
        $this->CompatibleRuntimes = empty($data['CompatibleRuntimes']) ? [] : $this->populateResultCompatibleRuntimes($data['CompatibleRuntimes']);
        $this->LicenseInfo = isset($data['LicenseInfo']) ? (string) $data['LicenseInfo'] : null;
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
