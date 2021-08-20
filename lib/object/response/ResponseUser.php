<?php

namespace KriKrixs\object\response;

use KriKrixs\object\User\User;

class ResponseUser extends Response
{
    private ?User $user = null;

    /**
     * Get user object
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set user object
     * @param User $user
     * @return ResponseUser
     */
    public function setUser(User $user): ResponseUser
    {
        $this->user = $user;
        return $this;
    }
}