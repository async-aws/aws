<?php

namespace AsyncAws\Rekognition\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Rekognition\Enum\EmotionName;
use AsyncAws\Rekognition\Enum\KnownGenderType;
use AsyncAws\Rekognition\Enum\LandmarkType;
use AsyncAws\Rekognition\Enum\OrientationCorrection;
use AsyncAws\Rekognition\ValueObject\BoundingBox;
use AsyncAws\Rekognition\ValueObject\Celebrity;
use AsyncAws\Rekognition\ValueObject\ComparedFace;
use AsyncAws\Rekognition\ValueObject\Emotion;
use AsyncAws\Rekognition\ValueObject\ImageQuality;
use AsyncAws\Rekognition\ValueObject\KnownGender;
use AsyncAws\Rekognition\ValueObject\Landmark;
use AsyncAws\Rekognition\ValueObject\Pose;
use AsyncAws\Rekognition\ValueObject\Smile;

class RecognizeCelebritiesResponse extends Result
{
    /**
     * Details about each celebrity found in the image. Amazon Rekognition can detect a maximum of 64 celebrities in an
     * image. Each celebrity object includes the following attributes: `Face`, `Confidence`, `Emotions`, `Landmarks`,
     * `Pose`, `Quality`, `Smile`, `Id`, `KnownGender`, `MatchConfidence`, `Name`, `Urls`.
     *
     * @var Celebrity[]
     */
    private $celebrityFaces;

    /**
     * Details about each unrecognized face in the image.
     *
     * @var ComparedFace[]
     */
    private $unrecognizedFaces;

    /**
     * > Support for estimating image orientation using the the OrientationCorrection field has ceased as of August 2021.
     * > Any returned values for this field included in an API response will always be NULL.
     *
     * The orientation of the input image (counterclockwise direction). If your application displays the image, you can use
     * this value to correct the orientation. The bounding box coordinates returned in `CelebrityFaces` and
     * `UnrecognizedFaces` represent face locations before the image orientation is corrected.
     *
     * > If the input image is in .jpeg format, it might contain exchangeable image (Exif) metadata that includes the
     * > image's orientation. If so, and the Exif metadata for the input image populates the orientation field, the value of
     * > `OrientationCorrection` is null. The `CelebrityFaces` and `UnrecognizedFaces` bounding box coordinates represent
     * > face locations after Exif metadata is used to correct the image orientation. Images in .png format don't contain
     * > Exif metadata.
     *
     * @var OrientationCorrection::*|null
     */
    private $orientationCorrection;

    /**
     * @return Celebrity[]
     */
    public function getCelebrityFaces(): array
    {
        $this->initialize();

        return $this->celebrityFaces;
    }

    /**
     * @return OrientationCorrection::*|null
     */
    public function getOrientationCorrection(): ?string
    {
        $this->initialize();

        return $this->orientationCorrection;
    }

    /**
     * @return ComparedFace[]
     */
    public function getUnrecognizedFaces(): array
    {
        $this->initialize();

        return $this->unrecognizedFaces;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->celebrityFaces = empty($data['CelebrityFaces']) ? [] : $this->populateResultCelebrityList($data['CelebrityFaces']);
        $this->unrecognizedFaces = empty($data['UnrecognizedFaces']) ? [] : $this->populateResultComparedFaceList($data['UnrecognizedFaces']);
        $this->orientationCorrection = isset($data['OrientationCorrection']) ? (!OrientationCorrection::exists((string) $data['OrientationCorrection']) ? OrientationCorrection::UNKNOWN_TO_SDK : (string) $data['OrientationCorrection']) : null;
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

    private function populateResultCelebrity(array $json): Celebrity
    {
        return new Celebrity([
            'Urls' => !isset($json['Urls']) ? null : $this->populateResultUrls($json['Urls']),
            'Name' => isset($json['Name']) ? (string) $json['Name'] : null,
            'Id' => isset($json['Id']) ? (string) $json['Id'] : null,
            'Face' => empty($json['Face']) ? null : $this->populateResultComparedFace($json['Face']),
            'MatchConfidence' => isset($json['MatchConfidence']) ? (float) $json['MatchConfidence'] : null,
            'KnownGender' => empty($json['KnownGender']) ? null : $this->populateResultKnownGender($json['KnownGender']),
        ]);
    }

    /**
     * @return Celebrity[]
     */
    private function populateResultCelebrityList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultCelebrity($item);
        }

        return $items;
    }

    private function populateResultComparedFace(array $json): ComparedFace
    {
        return new ComparedFace([
            'BoundingBox' => empty($json['BoundingBox']) ? null : $this->populateResultBoundingBox($json['BoundingBox']),
            'Confidence' => isset($json['Confidence']) ? (float) $json['Confidence'] : null,
            'Landmarks' => !isset($json['Landmarks']) ? null : $this->populateResultLandmarks($json['Landmarks']),
            'Pose' => empty($json['Pose']) ? null : $this->populateResultPose($json['Pose']),
            'Quality' => empty($json['Quality']) ? null : $this->populateResultImageQuality($json['Quality']),
            'Emotions' => !isset($json['Emotions']) ? null : $this->populateResultEmotions($json['Emotions']),
            'Smile' => empty($json['Smile']) ? null : $this->populateResultSmile($json['Smile']),
        ]);
    }

    /**
     * @return ComparedFace[]
     */
    private function populateResultComparedFaceList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultComparedFace($item);
        }

        return $items;
    }

    private function populateResultEmotion(array $json): Emotion
    {
        return new Emotion([
            'Type' => isset($json['Type']) ? (!EmotionName::exists((string) $json['Type']) ? EmotionName::UNKNOWN_TO_SDK : (string) $json['Type']) : null,
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

    private function populateResultImageQuality(array $json): ImageQuality
    {
        return new ImageQuality([
            'Brightness' => isset($json['Brightness']) ? (float) $json['Brightness'] : null,
            'Sharpness' => isset($json['Sharpness']) ? (float) $json['Sharpness'] : null,
        ]);
    }

    private function populateResultKnownGender(array $json): KnownGender
    {
        return new KnownGender([
            'Type' => isset($json['Type']) ? (!KnownGenderType::exists((string) $json['Type']) ? KnownGenderType::UNKNOWN_TO_SDK : (string) $json['Type']) : null,
        ]);
    }

    private function populateResultLandmark(array $json): Landmark
    {
        return new Landmark([
            'Type' => isset($json['Type']) ? (!LandmarkType::exists((string) $json['Type']) ? LandmarkType::UNKNOWN_TO_SDK : (string) $json['Type']) : null,
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

    /**
     * @return string[]
     */
    private function populateResultUrls(array $json): array
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
