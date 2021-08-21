<?php

namespace KriKrixs\object\beatmap;

class BeatMapMetadata
{
    private object $metadata;

    /**
     * Create a new BeatMapMetadata object
     * @param object $metadata
     */
    public function __construct(object $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * Return raw data
     * @return object
     */
    public function toJson(): object
    {
        return $this->metadata;
    }

    /**
     * Return array data
     * @return array
     */
    public function toArray(): array
    {
        return json_decode($this->metadata, true);
    }

    /**
     * Get metadata bpm
     * @return int
     */
    public function getBpm(): int
    {
        return $this->metadata->bpm;
    }

    /**
     * Get metadata duration (in seconds)
     * @return int
     */
    public function getDuration(): int
    {
        return $this->metadata->duration;
    }

    /**
     * Get metadata song name
     * @return string
     */
    public function getSongName(): string
    {
        return $this->metadata->songName;
    }

    /**
     * Get metadata song sub name
     * @return string
     */
    public function getSongSubName(): string
    {
        return $this->metadata->songSubName;
    }

    /**
     * Get metadata song author name
     * @return string
     */
    public function getSongAuthorName(): string
    {
        return $this->metadata->songAuthorName;
    }

    /**
     * Get metadata level author name
     * @return string
     */
    public function getLeverAuthorName(): string
    {
        return $this->metadata->levelAuthorName;
    }
}