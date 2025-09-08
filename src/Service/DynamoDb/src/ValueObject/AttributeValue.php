<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Represents the data for an attribute.
 *
 * Each attribute value is described as a name-value pair. The name is the data type, and the value is the data itself.
 *
 * For more information, see Data Types [^1] in the *Amazon DynamoDB Developer Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/HowItWorks.NamingRulesDataTypes.html#HowItWorks.DataTypes
 */
final class AttributeValue
{
    /**
     * An attribute of type String. For example:
     *
     * `"S": "Hello"`
     *
     * @var string|null
     */
    private $s;

    /**
     * An attribute of type Number. For example:
     *
     * `"N": "123.45"`
     *
     * Numbers are sent across the network to DynamoDB as strings, to maximize compatibility across languages and libraries.
     * However, DynamoDB treats them as number type attributes for mathematical operations.
     *
     * @var string|null
     */
    private $n;

    /**
     * An attribute of type Binary. For example:
     *
     * `"B": "dGhpcyB0ZXh0IGlzIGJhc2U2NC1lbmNvZGVk"`
     *
     * @var string|null
     */
    private $b;

    /**
     * An attribute of type String Set. For example:
     *
     * `"SS": ["Giraffe", "Hippo" ,"Zebra"]`
     *
     * @var string[]|null
     */
    private $ss;

    /**
     * An attribute of type Number Set. For example:
     *
     * `"NS": ["42.2", "-19", "7.5", "3.14"]`
     *
     * Numbers are sent across the network to DynamoDB as strings, to maximize compatibility across languages and libraries.
     * However, DynamoDB treats them as number type attributes for mathematical operations.
     *
     * @var string[]|null
     */
    private $ns;

    /**
     * An attribute of type Binary Set. For example:
     *
     * `"BS": ["U3Vubnk=", "UmFpbnk=", "U25vd3k="]`
     *
     * @var string[]|null
     */
    private $bs;

    /**
     * An attribute of type Map. For example:
     *
     * `"M": {"Name": {"S": "Joe"}, "Age": {"N": "35"}}`
     *
     * @var array<string, AttributeValue>|null
     */
    private $m;

    /**
     * An attribute of type List. For example:
     *
     * `"L": [ {"S": "Cookies"} , {"S": "Coffee"}, {"N": "3.14159"}]`
     *
     * @var AttributeValue[]|null
     */
    private $l;

    /**
     * An attribute of type Null. For example:
     *
     * `"NULL": true`
     *
     * @var bool|null
     */
    private $null;

    /**
     * An attribute of type Boolean. For example:
     *
     * `"BOOL": true`
     *
     * @var bool|null
     */
    private $bool;

    /**
     * @param array{
     *   S?: string|null,
     *   N?: string|null,
     *   B?: string|null,
     *   SS?: string[]|null,
     *   NS?: string[]|null,
     *   BS?: string[]|null,
     *   M?: array<string, AttributeValue|array>|null,
     *   L?: array<AttributeValue|array>|null,
     *   'NULL'?: bool|null,
     *   'BOOL'?: bool|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->s = $input['S'] ?? null;
        $this->n = $input['N'] ?? null;
        $this->b = $input['B'] ?? null;
        $this->ss = $input['SS'] ?? null;
        $this->ns = $input['NS'] ?? null;
        $this->bs = $input['BS'] ?? null;
        $this->m = isset($input['M']) ? array_map([AttributeValue::class, 'create'], $input['M']) : null;
        $this->l = isset($input['L']) ? array_map([AttributeValue::class, 'create'], $input['L']) : null;
        $this->null = $input['NULL'] ?? null;
        $this->bool = $input['BOOL'] ?? null;
    }

    /**
     * @param array{
     *   S?: string|null,
     *   N?: string|null,
     *   B?: string|null,
     *   SS?: string[]|null,
     *   NS?: string[]|null,
     *   BS?: string[]|null,
     *   M?: array<string, AttributeValue|array>|null,
     *   L?: array<AttributeValue|array>|null,
     *   'NULL'?: bool|null,
     *   'BOOL'?: bool|null,
     * }|AttributeValue $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getB(): ?string
    {
        return $this->b;
    }

    public function getBool(): ?bool
    {
        return $this->bool;
    }

    /**
     * @return string[]
     */
    public function getBs(): array
    {
        return $this->bs ?? [];
    }

    /**
     * @return AttributeValue[]
     */
    public function getL(): array
    {
        return $this->l ?? [];
    }

    /**
     * @return array<string, AttributeValue>
     */
    public function getM(): array
    {
        return $this->m ?? [];
    }

    public function getN(): ?string
    {
        return $this->n;
    }

    /**
     * @return string[]
     */
    public function getNs(): array
    {
        return $this->ns ?? [];
    }

    public function getNull(): ?bool
    {
        return $this->null;
    }

    public function getS(): ?string
    {
        return $this->s;
    }

    /**
     * @return string[]
     */
    public function getSs(): array
    {
        return $this->ss ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->s) {
            $payload['S'] = $v;
        }
        if (null !== $v = $this->n) {
            $payload['N'] = $v;
        }
        if (null !== $v = $this->b) {
            $payload['B'] = base64_encode($v);
        }
        if (null !== $v = $this->ss) {
            $index = -1;
            $payload['SS'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['SS'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->ns) {
            $index = -1;
            $payload['NS'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['NS'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->bs) {
            $index = -1;
            $payload['BS'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['BS'][$index] = base64_encode($listValue);
            }
        }
        if (null !== $v = $this->m) {
            if (empty($v)) {
                $payload['M'] = new \stdClass();
            } else {
                $payload['M'] = [];
                foreach ($v as $name => $mv) {
                    $payload['M'][$name] = $mv->requestBody();
                }
            }
        }
        if (null !== $v = $this->l) {
            $index = -1;
            $payload['L'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['L'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->null) {
            $payload['NULL'] = (bool) $v;
        }
        if (null !== $v = $this->bool) {
            $payload['BOOL'] = (bool) $v;
        }

        return $payload;
    }
}
