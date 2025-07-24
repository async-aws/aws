<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\TamsGapHandling;

/**
 * Specify a Time Addressable Media Store (TAMS) server as an input source. TAMS is an open-source API specification
 * that provides access to time-segmented media content. Use TAMS to retrieve specific time ranges from live or archived
 * media streams. When you specify TAMS settings, MediaConvert connects to your TAMS server, retrieves the media
 * segments for your specified time range, and processes them as a single input. This enables workflows like extracting
 * clips from live streams or processing specific portions of archived content. To use TAMS, you must: 1. Have access to
 * a TAMS-compliant server 2. Specify the server URL in the Input file URL field 3. Provide the required SourceId and
 * Timerange parameters 4. Configure authentication, if your TAMS server requires it.
 */
final class InputTamsSettings
{
    /**
     * Specify the ARN (Amazon Resource Name) of an EventBridge Connection to authenticate with your TAMS server. The
     * EventBridge Connection stores your authentication credentials securely. MediaConvert assumes your job's IAM role to
     * access this connection, so ensure the role has the events:RetrieveConnectionCredentials,
     * secretsmanager:DescribeSecret, and secretsmanager:GetSecretValue permissions. Format:
     * arn:aws:events:region:account-id:connection/connection-name/unique-id.
     *
     * @var string|null
     */
    private $authConnectionArn;

    /**
     * Specify how MediaConvert handles gaps between media segments in your TAMS source. Gaps can occur in live streams due
     * to network issues or other interruptions. Choose from the following options: * Skip gaps - Default. Skip over gaps
     * and join segments together. This creates a continuous output with no blank frames, but may cause timeline
     * discontinuities. * Fill with black - Insert black frames to fill gaps between segments. This maintains timeline
     * continuity but adds black frames where content is missing. * Hold last frame - Repeat the last frame before a gap
     * until the next segment begins. This maintains visual continuity during gaps.
     *
     * @var TamsGapHandling::*|string|null
     */
    private $gapHandling;

    /**
     * Specify the unique identifier for the media source in your TAMS server. MediaConvert uses this source ID to locate
     * the appropriate flows containing the media segments you want to process. The source ID corresponds to a specific
     * media source registered in your TAMS server. This source must be of type urn:x-nmos:format:multi, and can can
     * reference multiple flows for audio, video, or combined audio/video content. MediaConvert automatically selects the
     * highest quality flows available for your job. This setting is required when include TAMS settings in your job.
     *
     * @var string|null
     */
    private $sourceId;

    /**
     * Specify the time range of media segments to retrieve from your TAMS server. MediaConvert fetches only the segments
     * that fall within this range. Use the format specified by your TAMS server implementation. This must be two timestamp
     * values with the format {sign?}{seconds}:{nanoseconds}, separated by an underscore, surrounded by either parentheses
     * or square brackets. Example: [15:0_35:0) This setting is required when include TAMS settings in your job.
     *
     * @var string|null
     */
    private $timerange;

    /**
     * @param array{
     *   AuthConnectionArn?: null|string,
     *   GapHandling?: null|TamsGapHandling::*|string,
     *   SourceId?: null|string,
     *   Timerange?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->authConnectionArn = $input['AuthConnectionArn'] ?? null;
        $this->gapHandling = $input['GapHandling'] ?? null;
        $this->sourceId = $input['SourceId'] ?? null;
        $this->timerange = $input['Timerange'] ?? null;
    }

    /**
     * @param array{
     *   AuthConnectionArn?: null|string,
     *   GapHandling?: null|TamsGapHandling::*|string,
     *   SourceId?: null|string,
     *   Timerange?: null|string,
     * }|InputTamsSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAuthConnectionArn(): ?string
    {
        return $this->authConnectionArn;
    }

    /**
     * @return TamsGapHandling::*|string|null
     */
    public function getGapHandling(): ?string
    {
        return $this->gapHandling;
    }

    public function getSourceId(): ?string
    {
        return $this->sourceId;
    }

    public function getTimerange(): ?string
    {
        return $this->timerange;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->authConnectionArn) {
            $payload['authConnectionArn'] = $v;
        }
        if (null !== $v = $this->gapHandling) {
            if (!TamsGapHandling::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "gapHandling" for "%s". The value "%s" is not a valid "TamsGapHandling".', __CLASS__, $v));
            }
            $payload['gapHandling'] = $v;
        }
        if (null !== $v = $this->sourceId) {
            $payload['sourceId'] = $v;
        }
        if (null !== $v = $this->timerange) {
            $payload['timerange'] = $v;
        }

        return $payload;
    }
}
