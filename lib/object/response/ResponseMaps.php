<?php

namespace KriKrixs\object\response;

use KriKrixs\object\beatmap\BeatMap;

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

    /**
     * Set raw beatmaps
     * @param object $rawBeatMaps
     * @return ResponseMaps
     */
    public function setRawBeatMaps(object $rawBeatMaps): ResponseMaps
    {
        $beatMaps = [];

        foreach ($rawBeatMaps as $hash => $rawBeatMap) {
            $beatMaps[$hash] = $rawBeatMap === null || gettype($rawBeatMap) !== "object" ? null : new BeatMap($rawBeatMap);
        }

        $this->beatMaps = $beatMaps;
        return $this;
    }
}