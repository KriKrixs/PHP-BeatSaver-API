<?php

namespace KriKrixs\object\beatmap;

class BeatMapVersionDifficultyParitySummary
{
    private object $paritySummary;

    /**
     * Create a new BeatMapVersionDifficultyParitySummary object
     * @param object $paritySummary
     */
    public function __construct(object $paritySummary)
    {
        $this->paritySummary = $paritySummary;
    }

    /**
     * Return raw data
     * @return object
     */
    public function toJson(): object
    {
        return $this->paritySummary;
    }

    /**
     * Return array data
     * @return array
     */
    public function toArray(): array
    {
        return json_decode(json_encode($this->paritySummary), true);
    }

    /**
     * Get map version difficulty parity summary errors
     * @return int
     */
    public function getErrors(): int
    {
        return $this->paritySummary->errors;
    }

    /**
     * Get map version difficulty parity summary resets
     * @return int
     */
    public function getResets(): int
    {
        return $this->paritySummary->resets;
    }

    /**
     * Get map version difficulty parity summary warns
     * @return int
     */
    public function getWarns(): int
    {
        return $this->paritySummary->warns;
    }
}