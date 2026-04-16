<?php

namespace AsyncAws\Ec2\ValueObject;

use AsyncAws\Ec2\Enum\ProductCodeValues;

/**
 * Describes a product code.
 */
final class ProductCode
{
    /**
     * The product code.
     *
     * @var string|null
     */
    private $productCodeId;

    /**
     * The type of product code.
     *
     * @var ProductCodeValues::*|null
     */
    private $productCodeType;

    /**
     * @param array{
     *   ProductCodeId?: string|null,
     *   ProductCodeType?: ProductCodeValues::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->productCodeId = $input['ProductCodeId'] ?? null;
        $this->productCodeType = $input['ProductCodeType'] ?? null;
    }

    /**
     * @param array{
     *   ProductCodeId?: string|null,
     *   ProductCodeType?: ProductCodeValues::*|null,
     * }|ProductCode $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getProductCodeId(): ?string
    {
        return $this->productCodeId;
    }

    /**
     * @return ProductCodeValues::*|null
     */
    public function getProductCodeType(): ?string
    {
        return $this->productCodeType;
    }
}
