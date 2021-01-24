<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Structure containing attributes of the face that the algorithm detected.
 * A `FaceDetail` object contains either the default facial attributes or all facial attributes. The default attributes
 * are `BoundingBox`, `Confidence`, `Landmarks`, `Pose`, and `Quality`.
 * GetFaceDetection is the only Amazon Rekognition Video stored video operation that can return a `FaceDetail` object
 * with all attributes. To specify which attributes to return, use the `FaceAttributes` input parameter for
 * StartFaceDetection. The following Amazon Rekognition Video operations return only the default attributes. The
 * corresponding Start operations don't have a `FaceAttributes` input parameter.
 *
 * - GetCelebrityRecognition
 * - GetPersonTracking
 * - GetFaceSearch
 *
 * The Amazon Rekognition Image DetectFaces and IndexFaces operations can return all facial attributes. To specify which
 * attributes to return, use the `Attributes` input parameter for `DetectFaces`. For `IndexFaces`, use the
 * `DetectAttributes` input parameter.
 */
final class FaceDetail
{
    /**
     * Bounding box of the face. Default attribute.
     */
    private $BoundingBox;

    /**
     * The estimated age range, in years, for the face. Low represents the lowest estimated age and High represents the
     * highest estimated age.
     */
    private $AgeRange;

    /**
     * Indicates whether or not the face is smiling, and the confidence level in the determination.
     */
    private $Smile;

    /**
     * Indicates whether or not the face is wearing eye glasses, and the confidence level in the determination.
     */
    private $Eyeglasses;

    /**
     * Indicates whether or not the face is wearing sunglasses, and the confidence level in the determination.
     */
    private $Sunglasses;

    /**
     * The predicted gender of a detected face.
     */
    private $Gender;

    /**
     * Indicates whether or not the face has a beard, and the confidence level in the determination.
     */
    private $Beard;

    /**
     * Indicates whether or not the face has a mustache, and the confidence level in the determination.
     */
    private $Mustache;

    /**
     * Indicates whether or not the eyes on the face are open, and the confidence level in the determination.
     */
    private $EyesOpen;

    /**
     * Indicates whether or not the mouth on the face is open, and the confidence level in the determination.
     */
    private $MouthOpen;

    /**
     * The emotions that appear to be expressed on the face, and the confidence level in the determination. The API is only
     * making a determination of the physical appearance of a person's face. It is not a determination of the person’s
     * internal emotional state and should not be used in such a way. For example, a person pretending to have a sad face
     * might not be sad emotionally.
     */
    private $Emotions;

    /**
     * Indicates the location of landmarks on the face. Default attribute.
     */
    private $Landmarks;

    /**
     * Indicates the pose of the face as determined by its pitch, roll, and yaw. Default attribute.
     */
    private $Pose;

    /**
     * Identifies image brightness and sharpness. Default attribute.
     */
    private $Quality;

    /**
     * Confidence level that the bounding box contains a face (and not a different object such as a tree). Default
     * attribute.
     */
    private $Confidence;

    /**
     * @param array{
     *   BoundingBox?: null|BoundingBox|array,
     *   AgeRange?: null|AgeRange|array,
     *   Smile?: null|Smile|array,
     *   Eyeglasses?: null|Eyeglasses|array,
     *   Sunglasses?: null|Sunglasses|array,
     *   Gender?: null|Gender|array,
     *   Beard?: null|Beard|array,
     *   Mustache?: null|Mustache|array,
     *   EyesOpen?: null|EyeOpen|array,
     *   MouthOpen?: null|MouthOpen|array,
     *   Emotions?: null|Emotion[],
     *   Landmarks?: null|Landmark[],
     *   Pose?: null|Pose|array,
     *   Quality?: null|ImageQuality|array,
     *   Confidence?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->BoundingBox = isset($input['BoundingBox']) ? BoundingBox::create($input['BoundingBox']) : null;
        $this->AgeRange = isset($input['AgeRange']) ? AgeRange::create($input['AgeRange']) : null;
        $this->Smile = isset($input['Smile']) ? Smile::create($input['Smile']) : null;
        $this->Eyeglasses = isset($input['Eyeglasses']) ? Eyeglasses::create($input['Eyeglasses']) : null;
        $this->Sunglasses = isset($input['Sunglasses']) ? Sunglasses::create($input['Sunglasses']) : null;
        $this->Gender = isset($input['Gender']) ? Gender::create($input['Gender']) : null;
        $this->Beard = isset($input['Beard']) ? Beard::create($input['Beard']) : null;
        $this->Mustache = isset($input['Mustache']) ? Mustache::create($input['Mustache']) : null;
        $this->EyesOpen = isset($input['EyesOpen']) ? EyeOpen::create($input['EyesOpen']) : null;
        $this->MouthOpen = isset($input['MouthOpen']) ? MouthOpen::create($input['MouthOpen']) : null;
        $this->Emotions = isset($input['Emotions']) ? array_map([Emotion::class, 'create'], $input['Emotions']) : null;
        $this->Landmarks = isset($input['Landmarks']) ? array_map([Landmark::class, 'create'], $input['Landmarks']) : null;
        $this->Pose = isset($input['Pose']) ? Pose::create($input['Pose']) : null;
        $this->Quality = isset($input['Quality']) ? ImageQuality::create($input['Quality']) : null;
        $this->Confidence = $input['Confidence'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAgeRange(): ?AgeRange
    {
        return $this->AgeRange;
    }

    public function getBeard(): ?Beard
    {
        return $this->Beard;
    }

    public function getBoundingBox(): ?BoundingBox
    {
        return $this->BoundingBox;
    }

    public function getConfidence(): ?float
    {
        return $this->Confidence;
    }

    /**
     * @return Emotion[]
     */
    public function getEmotions(): array
    {
        return $this->Emotions ?? [];
    }

    public function getEyeglasses(): ?Eyeglasses
    {
        return $this->Eyeglasses;
    }

    public function getEyesOpen(): ?EyeOpen
    {
        return $this->EyesOpen;
    }

    public function getGender(): ?Gender
    {
        return $this->Gender;
    }

    /**
     * @return Landmark[]
     */
    public function getLandmarks(): array
    {
        return $this->Landmarks ?? [];
    }

    public function getMouthOpen(): ?MouthOpen
    {
        return $this->MouthOpen;
    }

    public function getMustache(): ?Mustache
    {
        return $this->Mustache;
    }

    public function getPose(): ?Pose
    {
        return $this->Pose;
    }

    public function getQuality(): ?ImageQuality
    {
        return $this->Quality;
    }

    public function getSmile(): ?Smile
    {
        return $this->Smile;
    }

    public function getSunglasses(): ?Sunglasses
    {
        return $this->Sunglasses;
    }
}
