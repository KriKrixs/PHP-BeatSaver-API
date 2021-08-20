<?php

namespace KriKrixs\object\response;

class ResponseMaps extends Response
{
    private array $beatMaps = [];

    /**
     * Get beatmaps (array of BeatMap object)
     * @return array
     */
    public function getBeatMaps(): array
    {
        return $this->beatMaps;
    }

    /**
     * Set beatmaps
     * @param array $beatMaps
     * @return ResponseMaps
     */
    public function setBeatMaps(array $beatMaps): ResponseMaps
    {
        $this->beatMaps = $beatMaps;
        return $this;
    }
}