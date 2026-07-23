<?php

namespace AsyncAws\Rekognition\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Rekognition\Enum\EmotionName;
use AsyncAws\Rekognition\Enum\LandmarkType;
use AsyncAws\Rekognition\Enum\OrientationCorrection;
use AsyncAws\Rekognition\ValueObject\BoundingBox;
use AsyncAws\Rekognition\ValueObject\ComparedFace;
use AsyncAws\Rekognition\ValueObject\ComparedSourceImageFace;
use AsyncAws\Rekognition\ValueObject\CompareFacesMatch;
use AsyncAws\Rekognition\ValueObject\Emotion;
use AsyncAws\Rekognition\ValueObject\ImageQuality;
use AsyncAws\Rekognition\ValueObject\Landmark;
use AsyncAws\Rekognition\ValueObject\Pose;
use AsyncAws\Rekognition\ValueObject\Smile;

class CompareFacesResponse extends Result
{
    /**
     * The face in the source image that was used for comparison.
     *
     * @var ComparedSourceImageFace|null
     */
    private $sourceImageFace;

    /**
     * An array of faces in the target image that match the source image face. Each `CompareFacesMatch` object provides the
     * bounding box, the confidence level that the bounding box contains a face, and the similarity score for the face in
     * the bounding box and the face in the source image.
     *
     * @var CompareFacesMatch[]
     */
    private $faceMatches;

    /**
     * An array of faces in the target image that did not match the source image face.
     *
     * @var ComparedFace[]
     */
    private $unmatchedFaces;

    /**
     * The value of `SourceImageOrientationCorrection` is always null.
     *
     * If the input image is in .jpeg format, it might contain exchangeable image file format (Exif) metadata that includes
     * the image's orientation. Amazon Rekognition uses this orientation information to perform image correction. The
     * bounding box coordinates are translated to represent object locations after the orientation information in the Exif
     * metadata is used to correct the image orientation. Images in .png format don't contain Exif metadata.
     *
     * Amazon Rekognition doesn’t perform image correction for images in .png format and .jpeg images without orientation
     * information in the image Exif metadata. The bounding box coordinates aren't translated and represent the object
     * locations before the image is rotated.
     *
     * @var OrientationCorrection::*|null
     */
    private $sourceImageOrientationCorrection;

    /**
     * The value of `TargetImageOrientationCorrection` is always null.
     *
     * If the input image is in .jpeg format, it might contain exchangeable image file format (Exif) metadata that includes
     * the image's orientation. Amazon Rekognition uses this orientation information to perform image correction. The
     * bounding box coordinates are translated to represent object locations after the orientation information in the Exif
     * metadata is used to correct the image orientation. Images in .png format don't contain Exif metadata.
     *
     * Amazon Rekognition doesn’t perform image correction for images in .png format and .jpeg images without orientation
     * information in the image Exif metadata. The bounding box coordinates aren't translated and represent the object
     * locations before the image is rotated.
     *
     * @var OrientationCorrection::*|null
     */
    private $targetImageOrientationCorrection;

    /**
     * @return CompareFacesMatch[]
     */
    public function getFaceMatches(): array
    {
        $this->initialize();

        return $this->faceMatches;
    }

    public function getSourceImageFace(): ?ComparedSourceImageFace
    {
        $this->initialize();

        return $this->sourceImageFace;
    }

    /**
     * @return OrientationCorrection::*|null
     */
    public function getSourceImageOrientationCorrection(): ?string
    {
        $this->initialize();

        return $this->sourceImageOrientationCorrection;
    }

    /**
     * @return OrientationCorrection::*|null
     */
    public function getTargetImageOrientationCorrection(): ?string
    {
        $this->initialize();

        return $this->targetImageOrientationCorrection;
    }

    /**
     * @return ComparedFace[]
     */
    public function getUnmatchedFaces(): array
    {
        $this->initialize();

        return $this->unmatchedFaces;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->sourceImageFace = empty($data['SourceImageFace']) ? null : $this->populateResultComparedSourceImageFace($data['SourceImageFace']);
        $this->faceMatches = empty($data['FaceMatches']) ? [] : $this->populateResultCompareFacesMatchList($data['FaceMatches']);
        $this->unmatchedFaces = empty($data['UnmatchedFaces']) ? [] : $this->populateResultCompareFacesUnmatchList($data['UnmatchedFaces']);
        $this->sourceImageOrientationCorrection = isset($data['SourceImageOrientationCorrection']) ? (!OrientationCorrection::exists((string) $data['SourceImageOrientationCorrection']) ? OrientationCorrection::UNKNOWN_TO_SDK : (string) $data['SourceImageOrientationCorrection']) : null;
        $this->targetImageOrientationCorrection = isset($data['TargetImageOrientationCorrection']) ? (!OrientationCorrection::exists((string) $data['TargetImageOrientationCorrection']) ? OrientationCorrection::UNKNOWN_TO_SDK : (string) $data['TargetImageOrientationCorrection']) : null;
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

    private function populateResultCompareFacesMatch(array $json): CompareFacesMatch
    {
        return new CompareFacesMatch([
            'Similarity' => isset($json['Similarity']) ? (float) $json['Similarity'] : null,
            'Face' => empty($json['Face']) ? null : $this->populateResultComparedFace($json['Face']),
        ]);
    }

    /**
     * @return CompareFacesMatch[]
     */
    private function populateResultCompareFacesMatchList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultCompareFacesMatch($item);
        }

        return $items;
    }

    /**
     * @return ComparedFace[]
     */
    private function populateResultCompareFacesUnmatchList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultComparedFace($item);
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

    private function populateResultComparedSourceImageFace(array $json): ComparedSourceImageFace
    {
        return new ComparedSourceImageFace([
            'BoundingBox' => empty($json['BoundingBox']) ? null : $this->populateResultBoundingBox($json['BoundingBox']),
            'Confidence' => isset($json['Confidence']) ? (float) $json['Confidence'] : null,
        ]);
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
}
