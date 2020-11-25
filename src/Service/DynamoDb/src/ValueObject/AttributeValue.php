<?php

namespace AsyncAws\DynamoDb\ValueObject;

final class AttributeValue
{
    /**
     * An attribute of type String. For example:.
     */
    private $S;

    /**
     * An attribute of type Number. For example:.
     */
    private $N;

    /**
     * An attribute of type Binary. For example:.
     */
    private $B;

    /**
     * An attribute of type String Set. For example:.
     */
    private $SS;

    /**
     * An attribute of type Number Set. For example:.
     */
    private $NS;

    /**
     * An attribute of type Binary Set. For example:.
     */
    private $BS;

    /**
     * An attribute of type Map. For example:.
     */
    private $M;

    /**
     * An attribute of type List. For example:.
     */
    private $L;

    /**
     * An attribute of type Null. For example:.
     */
    private $NULL;

    /**
     * An attribute of type Boolean. For example:.
     */
    private $BOOL;

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
        $this->S = $input['S'] ?? null;
        $this->N = $input['N'] ?? null;
        $this->B = $input['B'] ?? null;
        $this->SS = $input['SS'] ?? null;
        $this->NS = $input['NS'] ?? null;
        $this->BS = $input['BS'] ?? null;
        $this->M = isset($input['M']) ? array_map([AttributeValue::class, 'create'], $input['M']) : null;
        $this->L = isset($input['L']) ? array_map([AttributeValue::class, 'create'], $input['L']) : null;
        $this->NULL = $input['NULL'] ?? null;
        $this->BOOL = $input['BOOL'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getB(): ?string
    {
        return $this->B;
    }

    public function getBOOL(): ?bool
    {
        return $this->BOOL;
    }

    /**
     * @return string[]
     */
    public function getBS(): array
    {
        return $this->BS ?? [];
    }

    /**
     * @return AttributeValue[]
     */
    public function getL(): array
    {
        return $this->L ?? [];
    }

    /**
     * @return array<string, AttributeValue>
     */
    public function getM(): array
    {
        return $this->M ?? [];
    }

    public function getN(): ?string
    {
        return $this->N;
    }

    /**
     * @return string[]
     */
    public function getNS(): array
    {
        return $this->NS ?? [];
    }

    public function getNULL(): ?bool
    {
        return $this->NULL;
    }

    public function getS(): ?string
    {
        return $this->S;
    }

    /**
     * @return string[]
     */
    public function getSS(): array
    {
        return $this->SS ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->S) {
            $payload['S'] = $v;
        }
        if (null !== $v = $this->N) {
            $payload['N'] = $v;
        }
        if (null !== $v = $this->B) {
            $payload['B'] = base64_encode($v);
        }
        if (null !== $v = $this->SS) {
            $index = -1;
            $payload['SS'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['SS'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->NS) {
            $index = -1;
            $payload['NS'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['NS'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->BS) {
            $index = -1;
            $payload['BS'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['BS'][$index] = base64_encode($listValue);
            }
        }
        if (null !== $v = $this->M) {
            if (empty($v)) {
                $payload['M'] = new \stdClass();
            } else {
                $payload['M'] = [];
                foreach ($v as $name => $mv) {
                    $payload['M'][$name] = $mv->requestBody();
                }
            }
        }
        if (null !== $v = $this->L) {
            $index = -1;
            $payload['L'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['L'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->NULL) {
            $payload['NULL'] = (bool) $v;
        }
        if (null !== $v = $this->BOOL) {
            $payload['BOOL'] = (bool) $v;
        }

        return $payload;
    }
}
