<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Details of an Amazon EC2 AMI.
 */
final class Ami
{
    /**
     * The Amazon Web Services Region of the Amazon EC2 AMI.
     *
     * @var string|null
     */
    private $region;

    /**
     * The AMI ID of the Amazon EC2 AMI.
     *
     * @var string|null
     */
    private $image;

    /**
     * The name of the Amazon EC2 AMI.
     *
     * @var string|null
     */
    private $name;

    /**
     * The description of the Amazon EC2 AMI. Minimum and maximum length are in characters.
     *
     * @var string|null
     */
    private $description;

    /**
     * @var ImageState|null
     */
    private $state;

    /**
     * The account ID of the owner of the AMI.
     *
     * @var string|null
     */
    private $accountId;

    /**
     * @param array{
     *   region?: string|null,
     *   image?: string|null,
     *   name?: string|null,
     *   description?: string|null,
     *   state?: ImageState|array|null,
     *   accountId?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->region = $input['region'] ?? null;
        $this->image = $input['image'] ?? null;
        $this->name = $input['name'] ?? null;
        $this->description = $input['description'] ?? null;
        $this->state = isset($input['state']) ? ImageState::create($input['state']) : null;
        $this->accountId = $input['accountId'] ?? null;
    }

    /**
     * @param array{
     *   region?: string|null,
     *   image?: string|null,
     *   name?: string|null,
     *   description?: string|null,
     *   state?: ImageState|array|null,
     *   accountId?: string|null,
     * }|Ami $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function getState(): ?ImageState
    {
        return $this->state;
    }
}
