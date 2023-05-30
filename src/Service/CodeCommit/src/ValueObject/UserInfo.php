<?php

namespace AsyncAws\CodeCommit\ValueObject;

/**
 * Information about the user who made a specified commit.
 */
final class UserInfo
{
    /**
     * The name of the user who made the specified commit.
     */
    private $name;

    /**
     * The email address associated with the user who made the commit, if any.
     */
    private $email;

    /**
     * The date when the specified commit was commited, in timestamp format with GMT offset.
     */
    private $date;

    /**
     * @param array{
     *   name?: null|string,
     *   email?: null|string,
     *   date?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? null;
        $this->email = $input['email'] ?? null;
        $this->date = $input['date'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
