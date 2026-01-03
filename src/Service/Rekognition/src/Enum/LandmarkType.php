<?php

namespace AsyncAws\Rekognition\Enum;

final class LandmarkType
{
    public const CHIN_BOTTOM = 'chinBottom';
    public const EYE_LEFT = 'eyeLeft';
    public const EYE_RIGHT = 'eyeRight';
    public const LEFT_EYE_BROW_LEFT = 'leftEyeBrowLeft';
    public const LEFT_EYE_BROW_RIGHT = 'leftEyeBrowRight';
    public const LEFT_EYE_BROW_UP = 'leftEyeBrowUp';
    public const LEFT_EYE_DOWN = 'leftEyeDown';
    public const LEFT_EYE_LEFT = 'leftEyeLeft';
    public const LEFT_EYE_RIGHT = 'leftEyeRight';
    public const LEFT_EYE_UP = 'leftEyeUp';
    public const LEFT_PUPIL = 'leftPupil';
    public const MID_JAWLINE_LEFT = 'midJawlineLeft';
    public const MID_JAWLINE_RIGHT = 'midJawlineRight';
    public const MOUTH_DOWN = 'mouthDown';
    public const MOUTH_LEFT = 'mouthLeft';
    public const MOUTH_RIGHT = 'mouthRight';
    public const MOUTH_UP = 'mouthUp';
    public const NOSE = 'nose';
    public const NOSE_LEFT = 'noseLeft';
    public const NOSE_RIGHT = 'noseRight';
    public const RIGHT_EYE_BROW_LEFT = 'rightEyeBrowLeft';
    public const RIGHT_EYE_BROW_RIGHT = 'rightEyeBrowRight';
    public const RIGHT_EYE_BROW_UP = 'rightEyeBrowUp';
    public const RIGHT_EYE_DOWN = 'rightEyeDown';
    public const RIGHT_EYE_LEFT = 'rightEyeLeft';
    public const RIGHT_EYE_RIGHT = 'rightEyeRight';
    public const RIGHT_EYE_UP = 'rightEyeUp';
    public const RIGHT_PUPIL = 'rightPupil';
    public const UPPER_JAWLINE_LEFT = 'upperJawlineLeft';
    public const UPPER_JAWLINE_RIGHT = 'upperJawlineRight';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CHIN_BOTTOM => true,
            self::EYE_LEFT => true,
            self::EYE_RIGHT => true,
            self::LEFT_EYE_BROW_LEFT => true,
            self::LEFT_EYE_BROW_RIGHT => true,
            self::LEFT_EYE_BROW_UP => true,
            self::LEFT_EYE_DOWN => true,
            self::LEFT_EYE_LEFT => true,
            self::LEFT_EYE_RIGHT => true,
            self::LEFT_EYE_UP => true,
            self::LEFT_PUPIL => true,
            self::MID_JAWLINE_LEFT => true,
            self::MID_JAWLINE_RIGHT => true,
            self::MOUTH_DOWN => true,
            self::MOUTH_LEFT => true,
            self::MOUTH_RIGHT => true,
            self::MOUTH_UP => true,
            self::NOSE => true,
            self::NOSE_LEFT => true,
            self::NOSE_RIGHT => true,
            self::RIGHT_EYE_BROW_LEFT => true,
            self::RIGHT_EYE_BROW_RIGHT => true,
            self::RIGHT_EYE_BROW_UP => true,
            self::RIGHT_EYE_DOWN => true,
            self::RIGHT_EYE_LEFT => true,
            self::RIGHT_EYE_RIGHT => true,
            self::RIGHT_EYE_UP => true,
            self::RIGHT_PUPIL => true,
            self::UPPER_JAWLINE_LEFT => true,
            self::UPPER_JAWLINE_RIGHT => true,
        ][$value]);
    }
}
