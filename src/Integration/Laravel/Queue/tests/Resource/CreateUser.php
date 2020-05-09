<?php

declare(strict_types=1);

namespace AsyncAws\Illuminate\Queue\Tests\Resource;

class CreateUser
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
