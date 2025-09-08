<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Structure containing attributes of the face that the algorithm detected.
 *
 * A `FaceDetail` object contains either the default facial attributes or all facial attributes. The default attributes
 * are `BoundingBox`, `Confidence`, `Landmarks`, `Pose`, and `Quality`.
 *
 * GetFaceDetection is the only Amazon Rekognition Video stored video operation that can return a `FaceDetail` object
 * with all attributes. To specify which attributes to return, use the `FaceAttributes` input parameter for
 * StartFaceDetection. The following Amazon Rekognition Video operations return only the default attributes. The
 * corresponding Start operations don't have a `FaceAttributes` input parameter:
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
     *
     * @var BoundingBox|null
     */
    private $boundingBox;

    /**
     * The estimated age range, in years, for the face. Low represents the lowest estimated age and High represents the
     * highest estimated age.
     *
     * @var AgeRange|null
     */
    private $ageRange;

    /**
     * Indicates whether or not the face is smiling, and the confidence level in the determination.
     *
     * @var Smile|null
     */
    private $smile;

    /**
     * Indicates whether or not the face is wearing eye glasses, and the confidence level in the determination.
     *
     * @var Eyeglasses|null
     */
    private $eyeglasses;

    /**
     * Indicates whether or not the face is wearing sunglasses, and the confidence level in the determination.
     *
     * @var Sunglasses|null
     */
    private $sunglasses;

    /**
     * The predicted gender of a detected face.
     *
     * @var Gender|null
     */
    private $gender;

    /**
     * Indicates whether or not the face has a beard, and the confidence level in the determination.
     *
     * @var Beard|null
     */
    private $beard;

    /**
     * Indicates whether or not the face has a mustache, and the confidence level in the determination.
     *
     * @var Mustache|null
     */
    private $mustache;

    /**
     * Indicates whether or not the eyes on the face are open, and the confidence level in the determination.
     *
     * @var EyeOpen|null
     */
    private $eyesOpen;

    /**
     * Indicates whether or not the mouth on the face is open, and the confidence level in the determination.
     *
     * @var MouthOpen|null
     */
    private $mouthOpen;

    /**
     * The emotions that appear to be expressed on the face, and the confidence level in the determination. The API is only
     * making a determination of the physical appearance of a person's face. It is not a determination of the person’s
     * internal emotional state and should not be used in such a way. For example, a person pretending to have a sad face
     * might not be sad emotionally.
     *
     * @var Emotion[]|null
     */
    private $emotions;

    /**
     * Indicates the location of landmarks on the face. Default attribute.
     *
     * @var Landmark[]|null
     */
    private $landmarks;

    /**
     * Indicates the pose of the face as determined by its pitch, roll, and yaw. Default attribute.
     *
     * @var Pose|null
     */
    private $pose;

    /**
     * Identifies image brightness and sharpness. Default attribute.
     *
     * @var ImageQuality|null
     */
    private $quality;

    /**
     * Confidence level that the bounding box contains a face (and not a different object such as a tree). Default
     * attribute.
     *
     * @var float|null
     */
    private $confidence;

    /**
     * `FaceOccluded` should return "true" with a high confidence score if a detected face’s eyes, nose, and mouth are
     * partially captured or if they are covered by masks, dark sunglasses, cell phones, hands, or other objects.
     * `FaceOccluded` should return "false" with a high confidence score if common occurrences that do not impact face
     * verification are detected, such as eye glasses, lightly tinted sunglasses, strands of hair, and others.
     *
     * @var FaceOccluded|null
     */
    private $faceOccluded;

    /**
     * Indicates the direction the eyes are gazing in, as defined by pitch and yaw.
     *
     * @var EyeDirection|null
     */
    private $eyeDirection;

    /**
     * @param array{
     *   BoundingBox?: BoundingBox|array|null,
     *   AgeRange?: AgeRange|array|null,
     *   Smile?: Smile|array|null,
     *   Eyeglasses?: Eyeglasses|array|null,
     *   Sunglasses?: Sunglasses|array|null,
     *   Gender?: Gender|array|null,
     *   Beard?: Beard|array|null,
     *   Mustache?: Mustache|array|null,
     *   EyesOpen?: EyeOpen|array|null,
     *   MouthOpen?: MouthOpen|array|null,
     *   Emotions?: array<Emotion|array>|null,
     *   Landmarks?: array<Landmark|array>|null,
     *   Pose?: Pose|array|null,
     *   Quality?: ImageQuality|array|null,
     *   Confidence?: float|null,
     *   FaceOccluded?: FaceOccluded|array|null,
     *   EyeDirection?: EyeDirection|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->boundingBox = isset($input['BoundingBox']) ? BoundingBox::create($input['BoundingBox']) : null;
        $this->ageRange = isset($input['AgeRange']) ? AgeRange::create($input['AgeRange']) : null;
        $this->smile = isset($input['Smile']) ? Smile::create($input['Smile']) : null;
        $this->eyeglasses = isset($input['Eyeglasses']) ? Eyeglasses::create($input['Eyeglasses']) : null;
        $this->sunglasses = isset($input['Sunglasses']) ? Sunglasses::create($input['Sunglasses']) : null;
        $this->gender = isset($input['Gender']) ? Gender::create($input['Gender']) : null;
        $this->beard = isset($input['Beard']) ? Beard::create($input['Beard']) : null;
        $this->mustache = isset($input['Mustache']) ? Mustache::create($input['Mustache']) : null;
        $this->eyesOpen = isset($input['EyesOpen']) ? EyeOpen::create($input['EyesOpen']) : null;
        $this->mouthOpen = isset($input['MouthOpen']) ? MouthOpen::create($input['MouthOpen']) : null;
        $this->emotions = isset($input['Emotions']) ? array_map([Emotion::class, 'create'], $input['Emotions']) : null;
        $this->landmarks = isset($input['Landmarks']) ? array_map([Landmark::class, 'create'], $input['Landmarks']) : null;
        $this->pose = isset($input['Pose']) ? Pose::create($input['Pose']) : null;
        $this->quality = isset($input['Quality']) ? ImageQuality::create($input['Quality']) : null;
        $this->confidence = $input['Confidence'] ?? null;
        $this->faceOccluded = isset($input['FaceOccluded']) ? FaceOccluded::create($input['FaceOccluded']) : null;
        $this->eyeDirection = isset($input['EyeDirection']) ? EyeDirection::create($input['EyeDirection']) : null;
    }

    /**
     * @param array{
     *   BoundingBox?: BoundingBox|array|null,
     *   AgeRange?: AgeRange|array|null,
     *   Smile?: Smile|array|null,
     *   Eyeglasses?: Eyeglasses|array|null,
     *   Sunglasses?: Sunglasses|array|null,
     *   Gender?: Gender|array|null,
     *   Beard?: Beard|array|null,
     *   Mustache?: Mustache|array|null,
     *   EyesOpen?: EyeOpen|array|null,
     *   MouthOpen?: MouthOpen|array|null,
     *   Emotions?: array<Emotion|array>|null,
     *   Landmarks?: array<Landmark|array>|null,
     *   Pose?: Pose|array|null,
     *   Quality?: ImageQuality|array|null,
     *   Confidence?: float|null,
     *   FaceOccluded?: FaceOccluded|array|null,
     *   EyeDirection?: EyeDirection|array|null,
     * }|FaceDetail $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAgeRange(): ?AgeRange
    {
        return $this->ageRange;
    }

    public function getBeard(): ?Beard
    {
        return $this->beard;
    }

    public function getBoundingBox(): ?BoundingBox
    {
        return $this->boundingBox;
    }

    public function getConfidence(): ?float
    {
        return $this->confidence;
    }

    /**
     * @return Emotion[]
     */
    public function getEmotions(): array
    {
        return $this->emotions ?? [];
    }

    public function getEyeDirection(): ?EyeDirection
    {
        return $this->eyeDirection;
    }

    public function getEyeglasses(): ?Eyeglasses
    {
        return $this->eyeglasses;
    }

    public function getEyesOpen(): ?EyeOpen
    {
        return $this->eyesOpen;
    }

    public function getFaceOccluded(): ?FaceOccluded
    {
        return $this->faceOccluded;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    /**
     * @return Landmark[]
     */
    public function getLandmarks(): array
    {
        return $this->landmarks ?? [];
    }

    public function getMouthOpen(): ?MouthOpen
    {
        return $this->mouthOpen;
    }

    public function getMustache(): ?Mustache
    {
        return $this->mustache;
    }

    public function getPose(): ?Pose
    {
        return $this->pose;
    }

    public function getQuality(): ?ImageQuality
    {
        return $this->quality;
    }

    public function getSmile(): ?Smile
    {
        return $this->smile;
    }

    public function getSunglasses(): ?Sunglasses
    {
        return $this->sunglasses;
    }
}
