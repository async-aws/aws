<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class PublishLayerVersionResponse extends Result
{
    private $Content;

    private $LayerArn;

    private $LayerVersionArn;

    private $Description;

    private $CreatedDate;

    private $Version;

    private $CompatibleRuntimes = [];

    private $LicenseInfo;

    /**
     * @return string[]
     */
    public function getCompatibleRuntimes(): array
    {
        $this->initialize();

        return $this->CompatibleRuntimes;
    }

    /**
     * Details about the layer version.
     */
    public function getContent(): ?LayerVersionContentOutput
    {
        $this->initialize();

        return $this->Content;
    }

    /**
     * The date that the layer version was created, in ISO-8601 format (YYYY-MM-DDThh:mm:ss.sTZD).
     *
     * @see https://www.w3.org/TR/NOTE-datetime
     */
    public function getCreatedDate(): ?string
    {
        $this->initialize();

        return $this->CreatedDate;
    }

    /**
     * The description of the version.
     */
    public function getDescription(): ?string
    {
        $this->initialize();

        return $this->Description;
    }

    /**
     * The ARN of the layer.
     */
    public function getLayerArn(): ?string
    {
        $this->initialize();

        return $this->LayerArn;
    }

    /**
     * The ARN of the layer version.
     */
    public function getLayerVersionArn(): ?string
    {
        $this->initialize();

        return $this->LayerVersionArn;
    }

    /**
     * The layer's software license.
     */
    public function getLicenseInfo(): ?string
    {
        $this->initialize();

        return $this->LicenseInfo;
    }

    /**
     * The version number.
     */
    public function getVersion(): ?string
    {
        $this->initialize();

        return $this->Version;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $data = $response->toArray(false);

        $this->Content = new LayerVersionContentOutput([
            'Location' => isset($data['Content']['Location']) ? (string) $data['Content']['Location'] : null,
            'CodeSha256' => isset($data['Content']['CodeSha256']) ? (string) $data['Content']['CodeSha256'] : null,
            'CodeSize' => isset($data['Content']['CodeSize']) ? (string) $data['Content']['CodeSize'] : null,
        ]);
        $this->LayerArn = isset($data['LayerArn']) ? (string) $data['LayerArn'] : null;
        $this->LayerVersionArn = isset($data['LayerVersionArn']) ? (string) $data['LayerVersionArn'] : null;
        $this->Description = isset($data['Description']) ? (string) $data['Description'] : null;
        $this->CreatedDate = isset($data['CreatedDate']) ? (string) $data['CreatedDate'] : null;
        $this->Version = isset($data['Version']) ? (string) $data['Version'] : null;
        $this->CompatibleRuntimes = !$data['CompatibleRuntimes'] ? [] : (function (array $json): array {
            $items = [];
            foreach ($json as $item) {
                $a = isset($item) ? (string) $item : null;
                if (null !== $a) {
                    $items[] = $a;
                }
            }

            return $items;
        })($data['CompatibleRuntimes']);
        $this->LicenseInfo = isset($data['LicenseInfo']) ? (string) $data['LicenseInfo'] : null;
    }
}
