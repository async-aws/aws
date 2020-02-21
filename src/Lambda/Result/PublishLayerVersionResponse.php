<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

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
     * @return string[]
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

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $data = json_decode($response->getContent(false), true);

        $this->Content = new LayerVersionContentOutput([
            'Location' => ($v = $data['Content']['Location']) ? (string) $v : null,
            'CodeSha256' => ($v = $data['Content']['CodeSha256']) ? (string) $v : null,
            'CodeSize' => ($v = $data['Content']['CodeSize']) ? (string) $v : null,
        ]);
        $this->LayerArn = ($v = $data['LayerArn']) ? (string) $v : null;
        $this->LayerVersionArn = ($v = $data['LayerVersionArn']) ? (string) $v : null;
        $this->Description = ($v = $data['Description']) ? (string) $v : null;
        $this->CreatedDate = ($v = $data['CreatedDate']) ? (string) $v : null;
        $this->Version = ($v = $data['Version']) ? (string) $v : null;
        $this->CompatibleRuntimes = (function (array $json): array {
            $items = [];
            foreach ($json as $item) {
                $a = ($v = $item) ? (string) $v : null;
                if (null !== $a) {
                    $items[] = $a;
                }
            }

            return $items;
        })($data['CompatibleRuntimes']);
        $this->LicenseInfo = ($v = $data['LicenseInfo']) ? (string) $v : null;
    }
}
