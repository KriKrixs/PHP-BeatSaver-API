<?php

namespace KriKrixs\object\beatmap;

use DateTime;

class BeatMap
{
    private object $bm;

    /**
     * Create a new BeatMap object
     * @param object $beatmap
     */
    public function __construct(object $beatmap)
    {
        $this->bm = $beatmap;
    }

    /**
     * Return raw beatmap data
     * @return object
     */
    public function toJson(): object
    {
        return $this->bm;
    }

    /**
     * Get map ID (Same as BSR key)
     * @return string
     */
    public function getId(): string
    {
        return $this->bm->id;
    }

    /**
     * Get map BSR key (Same as ID)
     * @return string
     */
    public function getBsrKey(): string
    {
        return $this->bm->id;
    }

    /**
     * Get map name
     * @return string
     */
    public function getName(): string
    {
        return $this->bm->name;
    }

    /**
     * Get map description
     * @return string
     */
    public function getDescription(): string
    {
        return $this->bm->description;
    }

    /**
     * Get map upload date
     * @return DateTime|null
     */
    public function getUploadDate(): ?DateTime
    {
        try {
            return new DateTime($this->bm->uploaded);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get map creation date
     * @return DateTime|null
     */
    public function getCreateDate(): ?DateTime
    {
        try {
            return new DateTime($this->bm->createdAt);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get map update date
     * @return DateTime|null
     */
    public function getUpdateDate(): ?DateTime
    {
        try {
            return new DateTime($this->bm->updatedAt);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get map last publish date
     * @return DateTime|null
     */
    public function getLastPublishDate(): ?DateTime
    {
        try {
            return new DateTime($this->bm->lastPublishedAt);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Is the map auto mapped ?
     * @return bool
     */
    public function isAutoMapped(): bool
    {
        return boolval($this->bm->automapper);
    }

    /**
     * Is the map ranked ?
     * @return bool
     */
    public function isRanked(): bool
    {
        return boolval($this->bm->ranked);
    }

    /**
     * Is the map qualified for ranking ?
     * @return bool
     */
    public function isQualified(): bool
    {
        return boolval($this->bm->qualified);
    }

    //////////////
    /// Object ///
    //////////////

    /**
     * Get map uploader (object)
     * @return BeatMapUploader
     */
    public function getUploader(): BeatMapUploader
    {
        return new BeatMapUploader($this->bm->uploader);
    }

    /**
     * Get map metadata (object)
     * @return BeatMapMetadata
     */
    public function getMetadata(): BeatMapMetadata
    {
        return new BeatMapMetadata($this->bm->metadata);
    }

    /**
     * Get map stats (object)
     * @return BeatMapStats
     */
    public function getStats(): BeatMapStats
    {
        return new BeatMapStats($this->bm->stats);
    }

    /**
     * Get map versions (array of object)
     * @return array
     */
    public function getVersions(): array
    {
        $response = [];

        foreach($this->bm->versions as $version) {
            $response[] = new BeatMapVersion($version);
        }

        return $response;
    }
}