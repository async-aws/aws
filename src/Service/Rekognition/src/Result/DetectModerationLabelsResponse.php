<?php

namespace AsyncAws\Rekognition\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Rekognition\ValueObject\HumanLoopActivationOutput;
use AsyncAws\Rekognition\ValueObject\ModerationLabel;

class DetectModerationLabelsResponse extends Result
{
    /**
     * Array of detected Moderation labels and the time, in milliseconds from the start of the video, they were detected.
     */
    private $moderationLabels;

    /**
     * Version number of the moderation detection model that was used to detect unsafe content.
     */
    private $moderationModelVersion;

    /**
     * Shows the results of the human in the loop evaluation.
     */
    private $humanLoopActivationOutput;

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

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->moderationLabels = empty($data['ModerationLabels']) ? [] : $this->populateResultModerationLabels($data['ModerationLabels']);
        $this->moderationModelVersion = isset($data['ModerationModelVersion']) ? (string) $data['ModerationModelVersion'] : null;
        $this->humanLoopActivationOutput = empty($data['HumanLoopActivationOutput']) ? null : $this->populateResultHumanLoopActivationOutput($data['HumanLoopActivationOutput']);
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
