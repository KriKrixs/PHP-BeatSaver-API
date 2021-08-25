<?php

namespace KriKrixs\object\beatmap;

use DateTime;

class BeatMapVersion
{
    private object $version;

    /**
     * Create a new BeatMapVersion object
     * @param object $version
     */
    public function __construct(object $version)
    {
        $this->version = $version;
    }

    /**
     * Return raw data
     * @return object
     */
    public function toJson(): object
    {
        return $this->version;
    }

    /**
     * Return array data
     * @return array
     */
    public function toArray(): array
    {
        return json_decode(json_encode($this->version), true);
    }

    /**
     * Get map version hash
     * @return string
     */
    public function getHash(): string
    {
        return $this->version->hash;
    }

    /**
     * Get map version BSR key (same as map ID)
     * @return string
     */
    public function getBsrKey(): string
    {
        return $this->version->key;
    }

    /**
     * Get map version ID (same as BSR Key)
     * @return string
     */
    public function getId(): string
    {
        return $this->version->key;
    }

    /**
     * Get map version state
     * @return string
     */
    public function getState(): string
    {
        return $this->version->state;
    }

    /**
     * Get map version creation date
     * @return DateTime|null
     */
    public function getCreationDate(): ?DateTime
    {
        try {
            return new DateTime($this->version->createdAt);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get map version sage score
     * @return int
     */
    public function getSageScore(): int
    {
        return $this->version->sageScore;
    }

    /**
     * Get map version difficulties (array of object)
     * @return array
     */
    public function getDifficulties(): array
    {
        $response = [];

        foreach ($this->version->diffs as $diff) {
            $response[] = new BeatMapVersionDifficulty($diff);
        }

        return $response;
    }

    /**
     * Get map version download url
     * @return string
     */
    public function getDownloadURL(): string
    {
        return $this->version->downloadURL;
    }

    /**
     * Get map version cover url
     * @return string
     */
    public function getCoverURL(): string
    {
        return $this->version->coverURL;
    }

    /**
     * Get map version preview url
     * @return string
     */
    public function getPreviewURL(): string
    {
        return $this->version->previewURL;
    }
}