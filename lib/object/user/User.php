<?php

namespace KriKrixs\object\user;

class User
{
    private object $user;

    /**
     * Create a new User object
     * @param object $user
     */
    public function __construct(object $user)
    {
        $this->user = $user;
    }

    /**
     * Get user ID
     * @return int
     */
    public function getId(): int
    {
        return $this->user->id;
    }

    /**
     * Get user name
     * @return string
     */
    public function getName(): string
    {
        return $this->user->name;
    }

    /**
     * Is user unique set
     * @return bool
     */
    public function isUniqueSet(): bool
    {
        return $this->user->uniqueSet;
    }

    /**
     * Get user hash
     * @return string
     */
    public function getHash(): string
    {
        return $this->user->hash;
    }

    /**
     * Get user avatar link
     * @return string
     */
    public function getAvatarURL(): string
    {
        return $this->user->avatar;
    }

    /**
     * Get user stats (UserStats object)
     * @return UserStats
     */
    public function getStats(): UserStats
    {
        return new UserStats($this->user->stats);
    }

    /**
     * Get user type
     * @return string
     */
    public function getType(): string
    {
        return $this->user->type;
    }
}