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

    /**
     * @return Emotion[]
     */
    private function populateResultEmotions(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new Emotion([
                'Type' => isset($item['Type']) ? (string) $item['Type'] : null,
                'Confidence' => isset($item['Confidence']) ? (float) $item['Confidence'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return FaceDetail[]
     */
    private function populateResultFaceDetailList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new FaceDetail([
                'BoundingBox' => empty($item['BoundingBox']) ? null : new BoundingBox([
                    'Width' => isset($item['BoundingBox']['Width']) ? (float) $item['BoundingBox']['Width'] : null,
                    'Height' => isset($item['BoundingBox']['Height']) ? (float) $item['BoundingBox']['Height'] : null,
                    'Left' => isset($item['BoundingBox']['Left']) ? (float) $item['BoundingBox']['Left'] : null,
                    'Top' => isset($item['BoundingBox']['Top']) ? (float) $item['BoundingBox']['Top'] : null,
                ]),
                'AgeRange' => empty($item['AgeRange']) ? null : new AgeRange([
                    'Low' => isset($item['AgeRange']['Low']) ? (int) $item['AgeRange']['Low'] : null,
                    'High' => isset($item['AgeRange']['High']) ? (int) $item['AgeRange']['High'] : null,
                ]),
                'Smile' => empty($item['Smile']) ? null : new Smile([
                    'Value' => isset($item['Smile']['Value']) ? filter_var($item['Smile']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'Confidence' => isset($item['Smile']['Confidence']) ? (float) $item['Smile']['Confidence'] : null,
                ]),
                'Eyeglasses' => empty($item['Eyeglasses']) ? null : new Eyeglasses([
                    'Value' => isset($item['Eyeglasses']['Value']) ? filter_var($item['Eyeglasses']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'Confidence' => isset($item['Eyeglasses']['Confidence']) ? (float) $item['Eyeglasses']['Confidence'] : null,
                ]),
                'Sunglasses' => empty($item['Sunglasses']) ? null : new Sunglasses([
                    'Value' => isset($item['Sunglasses']['Value']) ? filter_var($item['Sunglasses']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'Confidence' => isset($item['Sunglasses']['Confidence']) ? (float) $item['Sunglasses']['Confidence'] : null,
                ]),
                'Gender' => empty($item['Gender']) ? null : new Gender([
                    'Value' => isset($item['Gender']['Value']) ? (string) $item['Gender']['Value'] : null,
                    'Confidence' => isset($item['Gender']['Confidence']) ? (float) $item['Gender']['Confidence'] : null,
                ]),
                'Beard' => empty($item['Beard']) ? null : new Beard([
                    'Value' => isset($item['Beard']['Value']) ? filter_var($item['Beard']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'Confidence' => isset($item['Beard']['Confidence']) ? (float) $item['Beard']['Confidence'] : null,
                ]),
                'Mustache' => empty($item['Mustache']) ? null : new Mustache([
                    'Value' => isset($item['Mustache']['Value']) ? filter_var($item['Mustache']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'Confidence' => isset($item['Mustache']['Confidence']) ? (float) $item['Mustache']['Confidence'] : null,
                ]),
                'EyesOpen' => empty($item['EyesOpen']) ? null : new EyeOpen([
                    'Value' => isset($item['EyesOpen']['Value']) ? filter_var($item['EyesOpen']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'Confidence' => isset($item['EyesOpen']['Confidence']) ? (float) $item['EyesOpen']['Confidence'] : null,
                ]),
                'MouthOpen' => empty($item['MouthOpen']) ? null : new MouthOpen([
                    'Value' => isset($item['MouthOpen']['Value']) ? filter_var($item['MouthOpen']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'Confidence' => isset($item['MouthOpen']['Confidence']) ? (float) $item['MouthOpen']['Confidence'] : null,
                ]),
                'Emotions' => empty($item['Emotions']) ? [] : $this->populateResultEmotions($item['Emotions']),
                'Landmarks' => empty($item['Landmarks']) ? [] : $this->populateResultLandmarks($item['Landmarks']),
                'Pose' => empty($item['Pose']) ? null : new Pose([
                    'Roll' => isset($item['Pose']['Roll']) ? (float) $item['Pose']['Roll'] : null,
                    'Yaw' => isset($item['Pose']['Yaw']) ? (float) $item['Pose']['Yaw'] : null,
                    'Pitch' => isset($item['Pose']['Pitch']) ? (float) $item['Pose']['Pitch'] : null,
                ]),
                'Quality' => empty($item['Quality']) ? null : new ImageQuality([
                    'Brightness' => isset($item['Quality']['Brightness']) ? (float) $item['Quality']['Brightness'] : null,
                    'Sharpness' => isset($item['Quality']['Sharpness']) ? (float) $item['Quality']['Sharpness'] : null,
                ]),
                'Confidence' => isset($item['Confidence']) ? (float) $item['Confidence'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return Landmark[]
     */
    private function populateResultLandmarks(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new Landmark([
                'Type' => isset($item['Type']) ? (string) $item['Type'] : null,
                'X' => isset($item['X']) ? (float) $item['X'] : null,
                'Y' => isset($item['Y']) ? (float) $item['Y'] : null,
            ]);
        }

        return $items;
    }
}
