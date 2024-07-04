<?php

namespace AsyncAws\Rekognition\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Rekognition\ValueObject\ContentType;
use AsyncAws\Rekognition\ValueObject\HumanLoopActivationOutput;
use AsyncAws\Rekognition\ValueObject\ModerationLabel;

class DetectModerationLabelsResponse extends Result
{
    /**
     * Array of detected Moderation labels. For video operations, this includes the time, in milliseconds from the start of
     * the video, they were detected.
     *
     * @var ModerationLabel[]
     */
    private $moderationLabels;

    /**
     * Version number of the base moderation detection model that was used to detect unsafe content.
     *
     * @var string|null
     */
    private $moderationModelVersion;

    /**
     * Shows the results of the human in the loop evaluation.
     *
     * @var HumanLoopActivationOutput|null
     */
    private $humanLoopActivationOutput;

    /**
     * Identifier of the custom adapter that was used during inference. If during inference the adapter was EXPIRED, then
     * the parameter will not be returned, indicating that a base moderation detection project version was used.
     *
     * @var string|null
     */
    private $projectVersion;

    /**
     * A list of predicted results for the type of content an image contains. For example, the image content might be from
     * animation, sports, or a video game.
     *
     * @var ContentType[]
     */
    private $contentTypes;

    /**
     * @return ContentType[]
     */
    public function getContentTypes(): array
    {
        $this->initialize();

        return $this->contentTypes;
    }

    public function getHumanLoopActivationOutput(): ?HumanLoopActivationOutput
    {
        $this->initialize();

        return $this->humanLoopActivationOutput;
    }

    /**
     * @return ModerationLabel[]
     */
    public function getModerationLabels(): array
    {
        $this->initialize();

        return $this->moderationLabels;
    }

    public function getModerationModelVersion(): ?string
    {
        $this->initialize();

        return $this->moderationModelVersion;
    }

    public function getProjectVersion(): ?string
    {
        $this->initialize();

        return $this->projectVersion;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->moderationLabels = empty($data['ModerationLabels']) ? [] : $this->populateResultModerationLabels($data['ModerationLabels']);
        $this->moderationModelVersion = isset($data['ModerationModelVersion']) ? (string) $data['ModerationModelVersion'] : null;
        $this->humanLoopActivationOutput = empty($data['HumanLoopActivationOutput']) ? null : $this->populateResultHumanLoopActivationOutput($data['HumanLoopActivationOutput']);
        $this->projectVersion = isset($data['ProjectVersion']) ? (string) $data['ProjectVersion'] : null;
        $this->contentTypes = empty($data['ContentTypes']) ? [] : $this->populateResultContentTypes($data['ContentTypes']);
    }

    private function populateResultContentType(array $json): ContentType
    {
        return new ContentType([
            'Confidence' => isset($json['Confidence']) ? (float) $json['Confidence'] : null,
            'Name' => isset($json['Name']) ? (string) $json['Name'] : null,
        ]);
    }

    /**
     * @return ContentType[]
     */
    private function populateResultContentTypes(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultContentType($item);
        }

        return $items;
    }

    private function populateResultHumanLoopActivationOutput(array $json): HumanLoopActivationOutput
    {
        return new HumanLoopActivationOutput([
            'HumanLoopArn' => isset($json['HumanLoopArn']) ? (string) $json['HumanLoopArn'] : null,
            'HumanLoopActivationReasons' => !isset($json['HumanLoopActivationReasons']) ? null : $this->populateResultHumanLoopActivationReasons($json['HumanLoopActivationReasons']),
            'HumanLoopActivationConditionsEvaluationResults' => isset($json['HumanLoopActivationConditionsEvaluationResults']) ? (string) $json['HumanLoopActivationConditionsEvaluationResults'] : null,
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultHumanLoopActivationReasons(array $json): array
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

    private function populateResultModerationLabel(array $json): ModerationLabel
    {
        return new ModerationLabel([
            'Confidence' => isset($json['Confidence']) ? (float) $json['Confidence'] : null,
            'Name' => isset($json['Name']) ? (string) $json['Name'] : null,
            'ParentName' => isset($json['ParentName']) ? (string) $json['ParentName'] : null,
            'TaxonomyLevel' => isset($json['TaxonomyLevel']) ? (int) $json['TaxonomyLevel'] : null,
        ]);
    }

    /**
     * @return ModerationLabel[]
     */
    private function populateResultModerationLabels(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultModerationLabel($item);
        }

        return $items;
    }
}
