<?php

namespace KriKrixs\object\beatmap;

class BeatMapVersionDifficulty
{
    private object $diff;

    /**
     * Create a new BeatMapVersionDifficulty object
     * @param object $diff
     */
    public function __construct(object $diff)
    {
        $this->diff = $diff;
    }

    /**
     * Return raw data
     * @return object
     */
    public function toJson(): object
    {
        return $this->diff;
    }

    /**
     * Return array data
     * @return array
     */
    public function toArray(): array
    {
        return json_decode(json_encode($this->diff), true);
    }

    /**
     * Get map version difficulty NJS
     * @return float
     */
    public function getNjs(): float
    {
        return $this->diff->njs;
    }

    /**
     * Get map version difficulty offset
     * @return float
     */
    public function getOffset(): float
    {
        return $this->diff->offset;
    }

    /**
     * Get map version difficulty notes
     * @return int
     */
    public function getNotes(): int
    {
        return $this->diff->notes;
    }

    /**
     * Get map version difficulty bombs
     * @return int
     */
    public function getBombs(): int
    {
        return $this->diff->bombs;
    }

    /**
     * Get map version difficulty obstacles
     * @return int
     */
    public function getObstacles(): int
    {
        return $this->diff->obstacles;
    }

    /**
     * Get map version difficulty NPS
     * @return float
     */
    public function getNps(): float
    {
        return $this->diff->nps;
    }

    /**
     * Get map version difficulty length
     * @return int
     */
    public function getLength(): int
    {
        return $this->diff->length;
    }

    /**
     * Get map version difficulty game mode
     * @return string
     */
    public function getGameMode(): string
    {
        return $this->diff->characteristic;
    }

    /**
     * Get map version difficulty game difficulty
     * @return string
     */
    public function getGameDifficulty(): string
    {
        return $this->diff->difficulty;
    }

    /**
     * Get map version difficulty events
     * @return int
     */
    public function getEvents(): int
    {
        return $this->diff->events;
    }

    /**
     * Get map version difficulty parity summary
     * @return BeatMapVersionDifficultyParitySummary
     */
    public function getParitySummary(): BeatMapVersionDifficultyParitySummary
    {
        return new BeatMapVersionDifficultyParitySummary($this->diff->paritySummary);
    }

    /**
     * Get map version difficulty seconds
     * @return int
     */
    public function getSeconds(): int
    {
        return $this->diff->seconds;
    }

    /**
     * Does map version difficulty require chroma
     * @return bool
     */
    public function isChromaRequired(): bool
    {
        return $this->diff->chroma;
    }

    /**
     * Does map version difficulty require mapping extensions
     * @return bool
     */
    public function isMappingExtensionsRequired(): bool
    {
        return $this->diff->me;
    }

    /**
     * Does map version difficulty require noodle extensions
     * @return bool
     */
    public function isNoodleExtensionsRequired(): bool
    {
        return $this->diff->ne;
    }

    /**
     * Is map version difficulty a cinema
     * @return bool
     */
    public function isCinema(): bool
    {
        return $this->diff->cinema;
    }
}