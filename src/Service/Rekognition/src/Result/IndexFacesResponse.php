<?php

namespace AsyncAws\Rekognition\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Rekognition\Enum\OrientationCorrection;
use AsyncAws\Rekognition\Enum\Reason;
use AsyncAws\Rekognition\ValueObject\AgeRange;
use AsyncAws\Rekognition\ValueObject\Beard;
use AsyncAws\Rekognition\ValueObject\BoundingBox;
use AsyncAws\Rekognition\ValueObject\Emotion;
use AsyncAws\Rekognition\ValueObject\Eyeglasses;
use AsyncAws\Rekognition\ValueObject\EyeOpen;
use AsyncAws\Rekognition\ValueObject\Face;
use AsyncAws\Rekognition\ValueObject\FaceDetail;
use AsyncAws\Rekognition\ValueObject\FaceRecord;
use AsyncAws\Rekognition\ValueObject\Gender;
use AsyncAws\Rekognition\ValueObject\ImageQuality;
use AsyncAws\Rekognition\ValueObject\Landmark;
use AsyncAws\Rekognition\ValueObject\MouthOpen;
use AsyncAws\Rekognition\ValueObject\Mustache;
use AsyncAws\Rekognition\ValueObject\Pose;
use AsyncAws\Rekognition\ValueObject\Smile;
use AsyncAws\Rekognition\ValueObject\Sunglasses;
use AsyncAws\Rekognition\ValueObject\UnindexedFace;

class IndexFacesResponse extends Result
{
    /**
     * An array of faces detected and added to the collection. For more information, see Searching Faces in a Collection in
     * the Amazon Rekognition Developer Guide.
     */
    private $faceRecords;

    /**
     * If your collection is associated with a face detection model that's later than version 3.0, the value of
     * `OrientationCorrection` is always null and no orientation information is returned.
     */
    private $orientationCorrection;

    /**
     * Latest face model being used with the collection. For more information, see Model versioning.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/face-detection-model.html
     */
    private $faceModelVersion;

    /**
     * An array of faces that were detected in the image but weren't indexed. They weren't indexed because the quality
     * filter identified them as low quality, or the `MaxFaces` request parameter filtered them out. To use the quality
     * filter, you specify the `QualityFilter` request parameter.
     */
    private $unindexedFaces;

    public function getFaceModelVersion(): ?string
    {
        $this->initialize();

        return $this->faceModelVersion;
    }

    /**
     * @return FaceRecord[]
     */
    public function getFaceRecords(): array
    {
        $this->initialize();

        return $this->faceRecords;
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
     * @return UnindexedFace[]
     */
    public function getUnindexedFaces(): array
    {
        $this->initialize();

        return $this->unindexedFaces;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->faceRecords = empty($data['FaceRecords']) ? [] : $this->populateResultFaceRecordList($data['FaceRecords']);
        $this->orientationCorrection = isset($data['OrientationCorrection']) ? (string) $data['OrientationCorrection'] : null;
        $this->faceModelVersion = isset($data['FaceModelVersion']) ? (string) $data['FaceModelVersion'] : null;
        $this->unindexedFaces = empty($data['UnindexedFaces']) ? [] : $this->populateResultUnindexedFaces($data['UnindexedFaces']);
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

    private function populateResultFace(array $json): Face
    {
        return new Face([
            'FaceId' => isset($json['FaceId']) ? (string) $json['FaceId'] : null,
            'BoundingBox' => empty($json['BoundingBox']) ? null : $this->populateResultBoundingBox($json['BoundingBox']),
            'ImageId' => isset($json['ImageId']) ? (string) $json['ImageId'] : null,
            'ExternalImageId' => isset($json['ExternalImageId']) ? (string) $json['ExternalImageId'] : null,
            'Confidence' => isset($json['Confidence']) ? (float) $json['Confidence'] : null,
            'IndexFacesModelVersion' => isset($json['IndexFacesModelVersion']) ? (string) $json['IndexFacesModelVersion'] : null,
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

    private function populateResultFaceRecord(array $json): FaceRecord
    {
        return new FaceRecord([
            'Face' => empty($json['Face']) ? null : $this->populateResultFace($json['Face']),
            'FaceDetail' => empty($json['FaceDetail']) ? null : $this->populateResultFaceDetail($json['FaceDetail']),
        ]);
    }

    /**
     * @return FaceRecord[]
     */
    private function populateResultFaceRecordList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultFaceRecord($item);
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

    /**
     * @return list<Reason::*>
     */
    private function populateResultReasons(array $json): array
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

    private function populateResultUnindexedFace(array $json): UnindexedFace
    {
        return new UnindexedFace([
            'Reasons' => !isset($json['Reasons']) ? null : $this->populateResultReasons($json['Reasons']),
            'FaceDetail' => empty($json['FaceDetail']) ? null : $this->populateResultFaceDetail($json['FaceDetail']),
        ]);
    }

    /**
     * @return UnindexedFace[]
     */
    private function populateResultUnindexedFaces(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultUnindexedFace($item);
        }

        return $items;
    }
}
