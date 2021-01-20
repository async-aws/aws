<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Represents the data for an attribute.
 * Each attribute value is described as a name-value pair. The name is the data type, and the value is the data itself.
 * For more information, see Data Types in the *Amazon DynamoDB Developer Guide*.
 *
 * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/HowItWorks.NamingRulesDataTypes.html#HowItWorks.DataTypes
 */
final class AttributeValue
{
    /**
     * An attribute of type String. For example:.
     */
    private $s;

    /**
     * An attribute of type Number. For example:.
     */
    private $n;

    /**
     * An attribute of type Binary. For example:.
     */
    private $b;

    /**
     * An attribute of type String Set. For example:.
     */
    private $sS;

    /**
     * An attribute of type Number Set. For example:.
     */
    private $nS;

    /**
     * An attribute of type Binary Set. For example:.
     */
    private $bS;

    /**
     * An attribute of type Map. For example:.
     */
    private $m;

    /**
     * An attribute of type List. For example:.
     */
    private $l;

    /**
     * An attribute of type Null. For example:.
     */
    private $nULL;

    /**
     * An attribute of type Boolean. For example:.
     */
    private $bOOL;

    /**
     * @param array{
     *   S?: null|string,
     *   N?: null|string,
     *   B?: null|string,
     *   SS?: null|string[],
     *   NS?: null|string[],
     *   BS?: null|string[],
     *   M?: null|array<string, AttributeValue>,
     *   L?: null|AttributeValue[],
     *   NULL?: null|bool,
     *   BOOL?: null|bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->s = $input['S'] ?? null;
        $this->n = $input['N'] ?? null;
        $this->b = $input['B'] ?? null;
        $this->sS = $input['SS'] ?? null;
        $this->nS = $input['NS'] ?? null;
        $this->bS = $input['BS'] ?? null;
        $this->m = isset($input['M']) ? array_map([AttributeValue::class, 'create'], $input['M']) : null;
        $this->l = isset($input['L']) ? array_map([AttributeValue::class, 'create'], $input['L']) : null;
        $this->nULL = $input['NULL'] ?? null;
        $this->bOOL = $input['BOOL'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getB(): ?string
    {
        return $this->b;
    }

    public function getBOOL(): ?bool
    {
        return $this->bOOL;
    }

    /**
     * @return string[]
     */
    public function getBS(): array
    {
        return $this->bS ?? [];
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
    public function getNS(): array
    {
        return $this->nS ?? [];
    }

    public function getNULL(): ?bool
    {
        return $this->nULL;
    }

    public function getS(): ?string
    {
        return $this->s;
    }

    /**
     * @return string[]
     */
    public function getSS(): array
    {
        return $this->sS ?? [];
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
        if (null !== $v = $this->sS) {
            $index = -1;
            $payload['SS'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['SS'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->nS) {
            $index = -1;
            $payload['NS'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['NS'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->bS) {
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
        if (null !== $v = $this->nULL) {
            $payload['NULL'] = (bool) $v;
        }
        if (null !== $v = $this->bOOL) {
            $payload['BOOL'] = (bool) $v;
        }

        return $payload;
    }
}
