<?php

namespace AsyncAws\Rekognition\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Rekognition\Enum\OrientationCorrection;
use AsyncAws\Rekognition\ValueObject\AgeRange;
use AsyncAws\Rekognition\ValueObject\Beard;
use AsyncAws\Rekognition\ValueObject\BoundingBox;
use AsyncAws\Rekognition\ValueObject\Emotion;
use AsyncAws\Rekognition\ValueObject\Eyeglasses;
use AsyncAws\Rekognition\ValueObject\EyeOpen;
use AsyncAws\Rekognition\ValueObject\FaceDetail;
use AsyncAws\Rekognition\ValueObject\Gender;
use AsyncAws\Rekognition\ValueObject\ImageQuality;
use AsyncAws\Rekognition\ValueObject\Landmark;
use AsyncAws\Rekognition\ValueObject\MouthOpen;
use AsyncAws\Rekognition\ValueObject\Mustache;
use AsyncAws\Rekognition\ValueObject\Pose;
use AsyncAws\Rekognition\ValueObject\Smile;
use AsyncAws\Rekognition\ValueObject\Sunglasses;

class DetectFacesResponse extends Result
{
    /**
     * Details of each face found in the image.
     */
    private $faceDetails;

    /**
     * The value of `OrientationCorrection` is always null.
     */
    private $orientationCorrection;

    /**
     * @return FaceDetail[]
     */
    public function getFaceDetails(): array
    {
        $this->initialize();

        return $this->faceDetails;
    }

    /**
     * @return OrientationCorrection::*|null
     */
    public function getOrientationCorrection(): ?string
    {
        $this->initialize();

        return $this->orientationCorrection;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->faceDetails = empty($data['FaceDetails']) ? [] : $this->populateResultFaceDetailList($data['FaceDetails']);
        $this->orientationCorrection = isset($data['OrientationCorrection']) ? (string) $data['OrientationCorrection'] : null;
    }

    private function populateResultAgeRange(array $json): AgeRange
    {
        return new AgeRange([
            'Low' => isset($json['Low']) ? (int) $json['Low'] : null,
            'High' => isset($json['High']) ? (int) $json['High'] : null,
        ]);
    }

    private function populateResultBeard(array $json): Beard
    {
        return new Beard([
            'Value' => isset($json['Value']) ? filter_var($json['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
            'Confidence' => isset($json['Confidence']) ? (float) $json['Confidence'] : null,
        ]);
    }

    private function populateResultBoundingBox(array $json): BoundingBox
    {
        return new BoundingBox([
            'Width' => isset($json['Width']) ? (float) $json['Width'] : null,
            'Height' => isset($json['Height']) ? (float) $json['Height'] : null,
            'Left' => isset($json['Left']) ? (float) $json['Left'] : null,
            'Top' => isset($json['Top']) ? (float) $json['Top'] : null,
        ]);
    }

    private function populateResultEmotion(array $json): Emotion
    {
        return new Emotion([
            'Type' => isset($json['Type']) ? (string) $json['Type'] : null,
            'Confidence' => isset($json['Confidence']) ? (float) $json['Confidence'] : null,
        ]);
    }

    /**
     * @return Emotion[]
     */
    private function populateResultEmotions(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultEmotion($item);
        }

        return $items;
    }

    private function populateResultEyeOpen(array $json): EyeOpen
    {
        return new EyeOpen([
            'Value' => isset($json['Value']) ? filter_var($json['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
            'Confidence' => isset($json['Confidence']) ? (float) $json['Confidence'] : null,
        ]);
    }

    private function populateResultEyeglasses(array $json): Eyeglasses
    {
        return new Eyeglasses([
            'Value' => isset($json['Value']) ? filter_var($json['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
            'Confidence' => isset($json['Confidence']) ? (float) $json['Confidence'] : null,
        ]);
    }

    private function populateResultFaceDetail(array $json): FaceDetail
    {
        return new FaceDetail([
            'BoundingBox' => empty($json['BoundingBox']) ? null : $this->populateResultBoundingBox($json['BoundingBox']),
            'AgeRange' => empty($json['AgeRange']) ? null : $this->populateResultAgeRange($json['AgeRange']),
            'Smile' => empty($json['Smile']) ? null : $this->populateResultSmile($json['Smile']),
            'Eyeglasses' => empty($json['Eyeglasses']) ? null : $this->populateResultEyeglasses($json['Eyeglasses']),
            'Sunglasses' => empty($json['Sunglasses']) ? null : $this->populateResultSunglasses($json['Sunglasses']),
            'Gender' => empty($json['Gender']) ? null : $this->populateResultGender($json['Gender']),
            'Beard' => empty($json['Beard']) ? null : $this->populateResultBeard($json['Beard']),
            'Mustache' => empty($json['Mustache']) ? null : $this->populateResultMustache($json['Mustache']),
            'EyesOpen' => empty($json['EyesOpen']) ? null : $this->populateResultEyeOpen($json['EyesOpen']),
            'MouthOpen' => empty($json['MouthOpen']) ? null : $this->populateResultMouthOpen($json['MouthOpen']),
            'Emotions' => !isset($json['Emotions']) ? null : $this->populateResultEmotions($json['Emotions']),
            'Landmarks' => !isset($json['Landmarks']) ? null : $this->populateResultLandmarks($json['Landmarks']),
            'Pose' => empty($json['Pose']) ? null : $this->populateResultPose($json['Pose']),
            'Quality' => empty($json['Quality']) ? null : $this->populateResultImageQuality($json['Quality']),
            'Confidence' => isset($json['Confidence']) ? (float) $json['Confidence'] : null,
        ]);
    }

    /**
     * @return FaceDetail[]
     */
    private function populateResultFaceDetailList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultFaceDetail($item);
        }

        return $items;
    }

    private function populateResultGender(array $json): Gender
    {
        return new Gender([
            'Value' => isset($json['Value']) ? (string) $json['Value'] : null,
            'Confidence' => isset($json['Confidence']) ? (float) $json['Confidence'] : null,
        ]);
    }

    private function populateResultImageQuality(array $json): ImageQuality
    {
        return new ImageQuality([
            'Brightness' => isset($json['Brightness']) ? (float) $json['Brightness'] : null,
            'Sharpness' => isset($json['Sharpness']) ? (float) $json['Sharpness'] : null,
        ]);
    }

    private function populateResultLandmark(array $json): Landmark
    {
        return new Landmark([
            'Type' => isset($json['Type']) ? (string) $json['Type'] : null,
            'X' => isset($json['X']) ? (float) $json['X'] : null,
            'Y' => isset($json['Y']) ? (float) $json['Y'] : null,
        ]);
    }

    /**
     * @return Landmark[]
     */
    private function populateResultLandmarks(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultLandmark($item);
        }

        return $items;
    }

    private function populateResultMouthOpen(array $json): MouthOpen
    {
        return new MouthOpen([
            'Value' => isset($json['Value']) ? filter_var($json['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
            'Confidence' => isset($json['Confidence']) ? (float) $json['Confidence'] : null,
        ]);
    }

    private function populateResultMustache(array $json): Mustache
    {
        return new Mustache([
            'Value' => isset($json['Value']) ? filter_var($json['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
            'Confidence' => isset($json['Confidence']) ? (float) $json['Confidence'] : null,
        ]);
    }

    private function populateResultPose(array $json): Pose
    {
        return new Pose([
            'Roll' => isset($json['Roll']) ? (float) $json['Roll'] : null,
            'Yaw' => isset($json['Yaw']) ? (float) $json['Yaw'] : null,
            'Pitch' => isset($json['Pitch']) ? (float) $json['Pitch'] : null,
        ]);
    }

    private function populateResultSmile(array $json): Smile
    {
        return new Smile([
            'Value' => isset($json['Value']) ? filter_var($json['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
            'Confidence' => isset($json['Confidence']) ? (float) $json['Confidence'] : null,
        ]);
    }

    private function populateResultSunglasses(array $json): Sunglasses
    {
        return new Sunglasses([
            'Value' => isset($json['Value']) ? filter_var($json['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
            'Confidence' => isset($json['Confidence']) ? (float) $json['Confidence'] : null,
        ]);
    }
}
