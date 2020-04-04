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
     *   M?: null|\AsyncAws\DynamoDb\ValueObject\AttributeValue[],
     *   L?: null|\AsyncAws\DynamoDb\ValueObject\AttributeValue[],
     *   NULL?: null|bool,
     *   BOOL?: null|bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->S = $input['S'] ?? null;
        $this->N = $input['N'] ?? null;
        $this->B = $input['B'] ?? null;
        $this->SS = $input['SS'] ?? [];
        $this->NS = $input['NS'] ?? [];
        $this->BS = $input['BS'] ?? [];
        $this->M = array_map([AttributeValue::class, 'create'], $input['M'] ?? []);
        $this->L = array_map([AttributeValue::class, 'create'], $input['L'] ?? []);
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
        return $this->BS;
    }

    /**
     * @return AttributeValue[]
     */
    public function getL(): array
    {
        return $this->L;
    }

    /**
     * @return AttributeValue[]
     */
    public function getM(): array
    {
        return $this->M;
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
        return $this->NS;
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
        return $this->SS;
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

        $index = -1;
        foreach ($this->SS as $listValue) {
            ++$index;
            $payload['SS'][$index] = $listValue;
        }

        $index = -1;
        foreach ($this->NS as $listValue) {
            ++$index;
            $payload['NS'][$index] = $listValue;
        }

        $index = -1;
        foreach ($this->BS as $listValue) {
            ++$index;
            $payload['BS'][$index] = base64_encode($listValue);
        }

        foreach ($this->M as $name => $v) {
            $payload['M'][$name] = $v->requestBody();
        }

        $index = -1;
        foreach ($this->L as $listValue) {
            ++$index;
            $payload['L'][$index] = $listValue->requestBody();
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
