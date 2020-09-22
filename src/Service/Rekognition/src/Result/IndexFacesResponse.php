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
    private $FaceRecords = [];

    /**
     * If your collection is associated with a face detection model that's later than version 3.0, the value of
     * `OrientationCorrection` is always null and no orientation information is returned.
     */
    private $OrientationCorrection;

    /**
     * The version number of the face detection model that's associated with the input collection (`CollectionId`).
     */
    private $FaceModelVersion;

    /**
     * An array of faces that were detected in the image but weren't indexed. They weren't indexed because the quality
     * filter identified them as low quality, or the `MaxFaces` request parameter filtered them out. To use the quality
     * filter, you specify the `QualityFilter` request parameter.
     */
    private $UnindexedFaces = [];

    public function getFaceModelVersion(): ?string
    {
        $this->initialize();

        return $this->FaceModelVersion;
    }

    /**
     * @return FaceRecord[]
     */
    public function getFaceRecords(): array
    {
        $this->initialize();

        return $this->FaceRecords;
    }

    /**
     * @return OrientationCorrection::*|null
     */
    public function getOrientationCorrection(): ?string
    {
        $this->initialize();

        return $this->OrientationCorrection;
    }

    /**
     * @return UnindexedFace[]
     */
    public function getUnindexedFaces(): array
    {
        $this->initialize();

        return $this->UnindexedFaces;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->FaceRecords = empty($data['FaceRecords']) ? [] : $this->populateResultFaceRecordList($data['FaceRecords']);
        $this->OrientationCorrection = isset($data['OrientationCorrection']) ? (string) $data['OrientationCorrection'] : null;
        $this->FaceModelVersion = isset($data['FaceModelVersion']) ? (string) $data['FaceModelVersion'] : null;
        $this->UnindexedFaces = empty($data['UnindexedFaces']) ? [] : $this->populateResultUnindexedFaces($data['UnindexedFaces']);
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
     * @return FaceRecord[]
     */
    private function populateResultFaceRecordList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new FaceRecord([
                'Face' => empty($item['Face']) ? null : new Face([
                    'FaceId' => isset($item['Face']['FaceId']) ? (string) $item['Face']['FaceId'] : null,
                    'BoundingBox' => empty($item['Face']['BoundingBox']) ? null : new BoundingBox([
                        'Width' => isset($item['Face']['BoundingBox']['Width']) ? (float) $item['Face']['BoundingBox']['Width'] : null,
                        'Height' => isset($item['Face']['BoundingBox']['Height']) ? (float) $item['Face']['BoundingBox']['Height'] : null,
                        'Left' => isset($item['Face']['BoundingBox']['Left']) ? (float) $item['Face']['BoundingBox']['Left'] : null,
                        'Top' => isset($item['Face']['BoundingBox']['Top']) ? (float) $item['Face']['BoundingBox']['Top'] : null,
                    ]),
                    'ImageId' => isset($item['Face']['ImageId']) ? (string) $item['Face']['ImageId'] : null,
                    'ExternalImageId' => isset($item['Face']['ExternalImageId']) ? (string) $item['Face']['ExternalImageId'] : null,
                    'Confidence' => isset($item['Face']['Confidence']) ? (float) $item['Face']['Confidence'] : null,
                ]),
                'FaceDetail' => empty($item['FaceDetail']) ? null : new FaceDetail([
                    'BoundingBox' => empty($item['FaceDetail']['BoundingBox']) ? null : new BoundingBox([
                        'Width' => isset($item['FaceDetail']['BoundingBox']['Width']) ? (float) $item['FaceDetail']['BoundingBox']['Width'] : null,
                        'Height' => isset($item['FaceDetail']['BoundingBox']['Height']) ? (float) $item['FaceDetail']['BoundingBox']['Height'] : null,
                        'Left' => isset($item['FaceDetail']['BoundingBox']['Left']) ? (float) $item['FaceDetail']['BoundingBox']['Left'] : null,
                        'Top' => isset($item['FaceDetail']['BoundingBox']['Top']) ? (float) $item['FaceDetail']['BoundingBox']['Top'] : null,
                    ]),
                    'AgeRange' => empty($item['FaceDetail']['AgeRange']) ? null : new AgeRange([
                        'Low' => isset($item['FaceDetail']['AgeRange']['Low']) ? (int) $item['FaceDetail']['AgeRange']['Low'] : null,
                        'High' => isset($item['FaceDetail']['AgeRange']['High']) ? (int) $item['FaceDetail']['AgeRange']['High'] : null,
                    ]),
                    'Smile' => empty($item['FaceDetail']['Smile']) ? null : new Smile([
                        'Value' => isset($item['FaceDetail']['Smile']['Value']) ? filter_var($item['FaceDetail']['Smile']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                        'Confidence' => isset($item['FaceDetail']['Smile']['Confidence']) ? (float) $item['FaceDetail']['Smile']['Confidence'] : null,
                    ]),
                    'Eyeglasses' => empty($item['FaceDetail']['Eyeglasses']) ? null : new Eyeglasses([
                        'Value' => isset($item['FaceDetail']['Eyeglasses']['Value']) ? filter_var($item['FaceDetail']['Eyeglasses']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                        'Confidence' => isset($item['FaceDetail']['Eyeglasses']['Confidence']) ? (float) $item['FaceDetail']['Eyeglasses']['Confidence'] : null,
                    ]),
                    'Sunglasses' => empty($item['FaceDetail']['Sunglasses']) ? null : new Sunglasses([
                        'Value' => isset($item['FaceDetail']['Sunglasses']['Value']) ? filter_var($item['FaceDetail']['Sunglasses']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                        'Confidence' => isset($item['FaceDetail']['Sunglasses']['Confidence']) ? (float) $item['FaceDetail']['Sunglasses']['Confidence'] : null,
                    ]),
                    'Gender' => empty($item['FaceDetail']['Gender']) ? null : new Gender([
                        'Value' => isset($item['FaceDetail']['Gender']['Value']) ? (string) $item['FaceDetail']['Gender']['Value'] : null,
                        'Confidence' => isset($item['FaceDetail']['Gender']['Confidence']) ? (float) $item['FaceDetail']['Gender']['Confidence'] : null,
                    ]),
                    'Beard' => empty($item['FaceDetail']['Beard']) ? null : new Beard([
                        'Value' => isset($item['FaceDetail']['Beard']['Value']) ? filter_var($item['FaceDetail']['Beard']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                        'Confidence' => isset($item['FaceDetail']['Beard']['Confidence']) ? (float) $item['FaceDetail']['Beard']['Confidence'] : null,
                    ]),
                    'Mustache' => empty($item['FaceDetail']['Mustache']) ? null : new Mustache([
                        'Value' => isset($item['FaceDetail']['Mustache']['Value']) ? filter_var($item['FaceDetail']['Mustache']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                        'Confidence' => isset($item['FaceDetail']['Mustache']['Confidence']) ? (float) $item['FaceDetail']['Mustache']['Confidence'] : null,
                    ]),
                    'EyesOpen' => empty($item['FaceDetail']['EyesOpen']) ? null : new EyeOpen([
                        'Value' => isset($item['FaceDetail']['EyesOpen']['Value']) ? filter_var($item['FaceDetail']['EyesOpen']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                        'Confidence' => isset($item['FaceDetail']['EyesOpen']['Confidence']) ? (float) $item['FaceDetail']['EyesOpen']['Confidence'] : null,
                    ]),
                    'MouthOpen' => empty($item['FaceDetail']['MouthOpen']) ? null : new MouthOpen([
                        'Value' => isset($item['FaceDetail']['MouthOpen']['Value']) ? filter_var($item['FaceDetail']['MouthOpen']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                        'Confidence' => isset($item['FaceDetail']['MouthOpen']['Confidence']) ? (float) $item['FaceDetail']['MouthOpen']['Confidence'] : null,
                    ]),
                    'Emotions' => empty($item['FaceDetail']['Emotions']) ? [] : $this->populateResultEmotions($item['FaceDetail']['Emotions']),
                    'Landmarks' => empty($item['FaceDetail']['Landmarks']) ? [] : $this->populateResultLandmarks($item['FaceDetail']['Landmarks']),
                    'Pose' => empty($item['FaceDetail']['Pose']) ? null : new Pose([
                        'Roll' => isset($item['FaceDetail']['Pose']['Roll']) ? (float) $item['FaceDetail']['Pose']['Roll'] : null,
                        'Yaw' => isset($item['FaceDetail']['Pose']['Yaw']) ? (float) $item['FaceDetail']['Pose']['Yaw'] : null,
                        'Pitch' => isset($item['FaceDetail']['Pose']['Pitch']) ? (float) $item['FaceDetail']['Pose']['Pitch'] : null,
                    ]),
                    'Quality' => empty($item['FaceDetail']['Quality']) ? null : new ImageQuality([
                        'Brightness' => isset($item['FaceDetail']['Quality']['Brightness']) ? (float) $item['FaceDetail']['Quality']['Brightness'] : null,
                        'Sharpness' => isset($item['FaceDetail']['Quality']['Sharpness']) ? (float) $item['FaceDetail']['Quality']['Sharpness'] : null,
                    ]),
                    'Confidence' => isset($item['FaceDetail']['Confidence']) ? (float) $item['FaceDetail']['Confidence'] : null,
                ]),
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

    /**
     * @return UnindexedFace[]
     */
    private function populateResultUnindexedFaces(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new UnindexedFace([
                'Reasons' => empty($item['Reasons']) ? [] : $this->populateResultReasons($item['Reasons']),
                'FaceDetail' => empty($item['FaceDetail']) ? null : new FaceDetail([
                    'BoundingBox' => empty($item['FaceDetail']['BoundingBox']) ? null : new BoundingBox([
                        'Width' => isset($item['FaceDetail']['BoundingBox']['Width']) ? (float) $item['FaceDetail']['BoundingBox']['Width'] : null,
                        'Height' => isset($item['FaceDetail']['BoundingBox']['Height']) ? (float) $item['FaceDetail']['BoundingBox']['Height'] : null,
                        'Left' => isset($item['FaceDetail']['BoundingBox']['Left']) ? (float) $item['FaceDetail']['BoundingBox']['Left'] : null,
                        'Top' => isset($item['FaceDetail']['BoundingBox']['Top']) ? (float) $item['FaceDetail']['BoundingBox']['Top'] : null,
                    ]),
                    'AgeRange' => empty($item['FaceDetail']['AgeRange']) ? null : new AgeRange([
                        'Low' => isset($item['FaceDetail']['AgeRange']['Low']) ? (int) $item['FaceDetail']['AgeRange']['Low'] : null,
                        'High' => isset($item['FaceDetail']['AgeRange']['High']) ? (int) $item['FaceDetail']['AgeRange']['High'] : null,
                    ]),
                    'Smile' => empty($item['FaceDetail']['Smile']) ? null : new Smile([
                        'Value' => isset($item['FaceDetail']['Smile']['Value']) ? filter_var($item['FaceDetail']['Smile']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                        'Confidence' => isset($item['FaceDetail']['Smile']['Confidence']) ? (float) $item['FaceDetail']['Smile']['Confidence'] : null,
                    ]),
                    'Eyeglasses' => empty($item['FaceDetail']['Eyeglasses']) ? null : new Eyeglasses([
                        'Value' => isset($item['FaceDetail']['Eyeglasses']['Value']) ? filter_var($item['FaceDetail']['Eyeglasses']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                        'Confidence' => isset($item['FaceDetail']['Eyeglasses']['Confidence']) ? (float) $item['FaceDetail']['Eyeglasses']['Confidence'] : null,
                    ]),
                    'Sunglasses' => empty($item['FaceDetail']['Sunglasses']) ? null : new Sunglasses([
                        'Value' => isset($item['FaceDetail']['Sunglasses']['Value']) ? filter_var($item['FaceDetail']['Sunglasses']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                        'Confidence' => isset($item['FaceDetail']['Sunglasses']['Confidence']) ? (float) $item['FaceDetail']['Sunglasses']['Confidence'] : null,
                    ]),
                    'Gender' => empty($item['FaceDetail']['Gender']) ? null : new Gender([
                        'Value' => isset($item['FaceDetail']['Gender']['Value']) ? (string) $item['FaceDetail']['Gender']['Value'] : null,
                        'Confidence' => isset($item['FaceDetail']['Gender']['Confidence']) ? (float) $item['FaceDetail']['Gender']['Confidence'] : null,
                    ]),
                    'Beard' => empty($item['FaceDetail']['Beard']) ? null : new Beard([
                        'Value' => isset($item['FaceDetail']['Beard']['Value']) ? filter_var($item['FaceDetail']['Beard']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                        'Confidence' => isset($item['FaceDetail']['Beard']['Confidence']) ? (float) $item['FaceDetail']['Beard']['Confidence'] : null,
                    ]),
                    'Mustache' => empty($item['FaceDetail']['Mustache']) ? null : new Mustache([
                        'Value' => isset($item['FaceDetail']['Mustache']['Value']) ? filter_var($item['FaceDetail']['Mustache']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                        'Confidence' => isset($item['FaceDetail']['Mustache']['Confidence']) ? (float) $item['FaceDetail']['Mustache']['Confidence'] : null,
                    ]),
                    'EyesOpen' => empty($item['FaceDetail']['EyesOpen']) ? null : new EyeOpen([
                        'Value' => isset($item['FaceDetail']['EyesOpen']['Value']) ? filter_var($item['FaceDetail']['EyesOpen']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                        'Confidence' => isset($item['FaceDetail']['EyesOpen']['Confidence']) ? (float) $item['FaceDetail']['EyesOpen']['Confidence'] : null,
                    ]),
                    'MouthOpen' => empty($item['FaceDetail']['MouthOpen']) ? null : new MouthOpen([
                        'Value' => isset($item['FaceDetail']['MouthOpen']['Value']) ? filter_var($item['FaceDetail']['MouthOpen']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                        'Confidence' => isset($item['FaceDetail']['MouthOpen']['Confidence']) ? (float) $item['FaceDetail']['MouthOpen']['Confidence'] : null,
                    ]),
                    'Emotions' => empty($item['FaceDetail']['Emotions']) ? [] : $this->populateResultEmotions($item['FaceDetail']['Emotions']),
                    'Landmarks' => empty($item['FaceDetail']['Landmarks']) ? [] : $this->populateResultLandmarks($item['FaceDetail']['Landmarks']),
                    'Pose' => empty($item['FaceDetail']['Pose']) ? null : new Pose([
                        'Roll' => isset($item['FaceDetail']['Pose']['Roll']) ? (float) $item['FaceDetail']['Pose']['Roll'] : null,
                        'Yaw' => isset($item['FaceDetail']['Pose']['Yaw']) ? (float) $item['FaceDetail']['Pose']['Yaw'] : null,
                        'Pitch' => isset($item['FaceDetail']['Pose']['Pitch']) ? (float) $item['FaceDetail']['Pose']['Pitch'] : null,
                    ]),
                    'Quality' => empty($item['FaceDetail']['Quality']) ? null : new ImageQuality([
                        'Brightness' => isset($item['FaceDetail']['Quality']['Brightness']) ? (float) $item['FaceDetail']['Quality']['Brightness'] : null,
                        'Sharpness' => isset($item['FaceDetail']['Quality']['Sharpness']) ? (float) $item['FaceDetail']['Quality']['Sharpness'] : null,
                    ]),
                    'Confidence' => isset($item['FaceDetail']['Confidence']) ? (float) $item['FaceDetail']['Confidence'] : null,
                ]),
            ]);
        }

        return $items;
    }
}
