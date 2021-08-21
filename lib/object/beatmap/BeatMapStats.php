<?php

namespace KriKrixs\object\beatmap;

class BeatMapStats
{
    private object $stats;

    /**
     * Create a new BeatMapStats object
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
     * Get map plays number
     * @return int
     */
    public function getPlaysNumber(): int
    {
        return $this->stats->plays;
    }

    /**
     * Get map download number
     * @return int
     */
    public function getDownloadsNumber(): int
    {
        return $this->stats->downloads;
    }

    /**
     * Get map upvotes number
     * @return int
     */
    public function getUpvotesNumber(): int
    {
        return $this->stats->upvotes;
    }

    /**
     * Get map downvotes number
     * @return int
     */
    public function getDownvotesNumber(): int
    {
        return $this->stats->downvotes;
    }

    /**
     * Get votes ratio
     * @return int
     */
    public function getVotesRatio(): float
    {
        return $this->stats->score;
    }
}