<?php

namespace KriKrixs\object\user;

use DateTime;

class UserStats
{
    private object $stats;

    /**
     * Create a new UserStats object
     * @param object $stats
     */
    public function __construct(object $stats)
    {
        $this->stats = $stats;
    }

    /**
     * Return raw data
     * @return object
     */
    public function toJson(): object
    {
        return $this->stats;
    }

    /**
     * Return array data
     * @return array
     */
    public function toArray(): array
    {
        return json_decode($this->stats, true);
    }

    /**
     * Get user stats total upvotes
     * @return int
     */
    public function getTotalUpvotes(): int
    {
        return $this->stats->totalUpvotes;
    }

    /**
     * Get user stats total downvotes
     * @return int
     */
    public function getTotalDownvotes(): int
    {
        return $this->stats->totalDownvotes;
    }

    /**
     * Get user stats total maps
     * @return int
     */
    public function getTotalMaps(): int
    {
        return $this->stats->totalMaps;
    }

    /**
     * Get user stats total ranked maps
     * @return int
     */
    public function getTotalRankedMaps(): int
    {
        return $this->stats->rankedMaps;
    }

    /**
     * Get user stats average bpm
     * @return float
     */
    public function getAverageBPM(): float
    {
        return $this->stats->avgBpm;
    }

    /**
     * Get user stats average score
     * @return float
     */
    public function getAverageScore(): float
    {
        return $this->stats->avgScore;
    }

    /**
     * Get user stats average duration
     * @return int
     */
    public function getAverageDuration(): int
    {
        return $this->stats->avgDuration;
    }

    /**
     * Get user stats first upload date
     * @return DateTime|null
     */
    public function getFirstUploadDate(): ?DateTime
    {
        try {
            return new DateTime($this->stats->firstUpload);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get user stats last upload date
     * @return DateTime|null
     */
    public function getLastUploadDate(): ?DateTime
    {
        try {
            return new DateTime($this->stats->lastUpload);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get UserStatsDifficulties object
     * @return UserStatsDifficulties
     */
    public function getDifficulties(): UserStatsDifficulties
    {
        return new UserStatsDifficulties($this->stats->diffStats);
    }
}