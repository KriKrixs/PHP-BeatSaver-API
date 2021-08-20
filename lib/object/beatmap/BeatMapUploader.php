<?php

namespace KriKrixs\object\beatmap;

class BeatMapUploader
{
    private object $uploader;

    /**
     * Create a new BeatMapUploader object
     * @param object $uploader
     */
    public function __construct(object $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * Get uploader ID
     * @return int
     */
    public function getId(): int
    {
        return $this->uploader->id;
    }

    /**
     * Get uploader name
     * @return string
     */
    public function getName(): string
    {
        return $this->uploader->name;
    }

    /**
     * Get uploader hash
     * @return string
     */
    public function getHash(): string
    {
        return $this->uploader->hash;
    }

    /**
     * Get uploader avatar link
     * @return string
     */
    public function getAvatarURL(): string
    {
        return $this->uploader->avatar;
    }

    /**
     * Get uploader type
     * @return string
     */
    public function getType(): string
    {
        return $this->uploader->type;
    }


    /**
     * Is uploader unique set ?
     * @return bool
     */
    public function isUniqueSet(): bool
    {
        return $this->uploader->uniqueSet;
    }
}