<?php

namespace KriKrixs\object\user;

class UserStatsDifficulties
{
    private object $diff;

    /**
     * Create a new UserStatsDifficulties object
     * @param object $diff
     */
    public function __construct(object $diff)
    {
        $this->diff = $diff;
    }

    /**
     * Get user stats difficulties total
     * @return int
     */
    public function getTotal(): int
    {
        return $this->diff->total;
    }

    /**
     * Get user stats difficulties easy number
     * @return int
     */
    public function getEasyNumber(): int
    {
        return $this->diff->easy;
    }

    /**
     * Get user stats difficulties normal number
     * @return int
     */
    public function getNormalNumber(): int
    {
        return $this->diff->normal;
    }

    /**
     * Get user stats difficulties hard number
     * @return int
     */
    public function getHardNumber(): int
    {
        return $this->diff->hard;
    }

    /**
     * Get user stats difficulties expert number
     * @return int
     */
    public function getExpertNumber(): int
    {
        return $this->diff->expert;
    }

    /**
     * Get user stats difficulties expert+ number
     * @return int
     */
    public function getExpertPlusNumber(): int
    {
        return $this->diff->expertPlus;
    }
}