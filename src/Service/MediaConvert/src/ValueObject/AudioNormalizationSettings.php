<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\AudioNormalizationAlgorithm;
use AsyncAws\MediaConvert\Enum\AudioNormalizationAlgorithmControl;
use AsyncAws\MediaConvert\Enum\AudioNormalizationLoudnessLogging;
use AsyncAws\MediaConvert\Enum\AudioNormalizationPeakCalculation;

/**
 * Advanced audio normalization settings. Ignore these settings unless you need to comply with a loudness standard.
 */
final class AudioNormalizationSettings
{
    /**
     * Choose one of the following audio normalization algorithms: ITU-R BS.1770-1: Ungated loudness. A measurement of
     * ungated average loudness for an entire piece of content, suitable for measurement of short-form content under ATSC
     * recommendation A/85. Supports up to 5.1 audio channels. ITU-R BS.1770-2: Gated loudness. A measurement of gated
     * average loudness compliant with the requirements of EBU-R128. Supports up to 5.1 audio channels. ITU-R BS.1770-3:
     * Modified peak. The same loudness measurement algorithm as 1770-2, with an updated true peak measurement. ITU-R
     * BS.1770-4: Higher channel count. Allows for more audio channels than the other algorithms, including configurations
     * such as 7.1.
     *
     * @var AudioNormalizationAlgorithm::*|null
     */
    private $algorithm;

    /**
     * When enabled the output audio is corrected using the chosen algorithm. If disabled, the audio will be measured but
     * not adjusted.
     *
     * @var AudioNormalizationAlgorithmControl::*|null
     */
    private $algorithmControl;

    /**
     * Content measuring above this level will be corrected to the target level. Content measuring below this level will not
     * be corrected.
     *
     * @var int|null
     */
    private $correctionGateLevel;

    /**
     * If set to LOG, log each output's audio track loudness to a CSV file.
     *
     * @var AudioNormalizationLoudnessLogging::*|null
     */
    private $loudnessLogging;

    /**
     * If set to TRUE_PEAK, calculate and log the TruePeak for each output's audio track loudness.
     *
     * @var AudioNormalizationPeakCalculation::*|null
     */
    private $peakCalculation;

    /**
     * When you use Audio normalization, optionally use this setting to specify a target loudness. If you don't specify a
     * value here, the encoder chooses a value for you, based on the algorithm that you choose for Algorithm. If you choose
     * algorithm 1770-1, the encoder will choose -24 LKFS; otherwise, the encoder will choose -23 LKFS.
     *
     * @var float|null
     */
    private $targetLkfs;

    /**
     * Specify the True-peak limiter threshold in decibels relative to full scale (dBFS). The peak inter-audio sample
     * loudness in your output will be limited to the value that you specify, without affecting the overall target LKFS.
     * Enter a value from 0 to -8. Leave blank to use the default value 0.
     *
     * @var float|null
     */
    private $truePeakLimiterThreshold;

    /**
     * @param array{
     *   Algorithm?: AudioNormalizationAlgorithm::*|null,
     *   AlgorithmControl?: AudioNormalizationAlgorithmControl::*|null,
     *   CorrectionGateLevel?: int|null,
     *   LoudnessLogging?: AudioNormalizationLoudnessLogging::*|null,
     *   PeakCalculation?: AudioNormalizationPeakCalculation::*|null,
     *   TargetLkfs?: float|null,
     *   TruePeakLimiterThreshold?: float|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->algorithm = $input['Algorithm'] ?? null;
        $this->algorithmControl = $input['AlgorithmControl'] ?? null;
        $this->correctionGateLevel = $input['CorrectionGateLevel'] ?? null;
        $this->loudnessLogging = $input['LoudnessLogging'] ?? null;
        $this->peakCalculation = $input['PeakCalculation'] ?? null;
        $this->targetLkfs = $input['TargetLkfs'] ?? null;
        $this->truePeakLimiterThreshold = $input['TruePeakLimiterThreshold'] ?? null;
    }

    /**
     * @param array{
     *   Algorithm?: AudioNormalizationAlgorithm::*|null,
     *   AlgorithmControl?: AudioNormalizationAlgorithmControl::*|null,
     *   CorrectionGateLevel?: int|null,
     *   LoudnessLogging?: AudioNormalizationLoudnessLogging::*|null,
     *   PeakCalculation?: AudioNormalizationPeakCalculation::*|null,
     *   TargetLkfs?: float|null,
     *   TruePeakLimiterThreshold?: float|null,
     * }|AudioNormalizationSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AudioNormalizationAlgorithm::*|null
     */
    public function getAlgorithm(): ?string
    {
        return $this->algorithm;
    }

    /**
     * @return AudioNormalizationAlgorithmControl::*|null
     */
    public function getAlgorithmControl(): ?string
    {
        return $this->algorithmControl;
    }

    public function getCorrectionGateLevel(): ?int
    {
        return $this->correctionGateLevel;
    }

    /**
     * @return AudioNormalizationLoudnessLogging::*|null
     */
    public function getLoudnessLogging(): ?string
    {
        return $this->loudnessLogging;
    }

    /**
     * @return AudioNormalizationPeakCalculation::*|null
     */
    public function getPeakCalculation(): ?string
    {
        return $this->peakCalculation;
    }

    public function getTargetLkfs(): ?float
    {
        return $this->targetLkfs;
    }

    public function getTruePeakLimiterThreshold(): ?float
    {
        return $this->truePeakLimiterThreshold;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->algorithm) {
            if (!AudioNormalizationAlgorithm::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "algorithm" for "%s". The value "%s" is not a valid "AudioNormalizationAlgorithm".', __CLASS__, $v));
            }
            $payload['algorithm'] = $v;
        }
        if (null !== $v = $this->algorithmControl) {
            if (!AudioNormalizationAlgorithmControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "algorithmControl" for "%s". The value "%s" is not a valid "AudioNormalizationAlgorithmControl".', __CLASS__, $v));
            }
            $payload['algorithmControl'] = $v;
        }
        if (null !== $v = $this->correctionGateLevel) {
            $payload['correctionGateLevel'] = $v;
        }
        if (null !== $v = $this->loudnessLogging) {
            if (!AudioNormalizationLoudnessLogging::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "loudnessLogging" for "%s". The value "%s" is not a valid "AudioNormalizationLoudnessLogging".', __CLASS__, $v));
            }
            $payload['loudnessLogging'] = $v;
        }
        if (null !== $v = $this->peakCalculation) {
            if (!AudioNormalizationPeakCalculation::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "peakCalculation" for "%s". The value "%s" is not a valid "AudioNormalizationPeakCalculation".', __CLASS__, $v));
            }
            $payload['peakCalculation'] = $v;
        }
        if (null !== $v = $this->targetLkfs) {
            $payload['targetLkfs'] = $v;
        }
        if (null !== $v = $this->truePeakLimiterThreshold) {
            $payload['truePeakLimiterThreshold'] = $v;
        }

        return $payload;
    }
}
