<?php

namespace KriKrixs\object\response;

use KriKrixs\object\beatmap\BeatMap;

class ResponseMap extends Response
{
    private ?BeatMap $beatMap = null;

    /**
     * Get beatmap object
     * @return BeatMap|null
     */
    public function getBeatMap(): ?BeatMap
    {
        return $this->beatMap;
    }

    /**
     * Set beatmap object
     * @param BeatMap $beatMap
     * @return ResponseMap
     */
    public function setBeatMap(BeatMap $beatMap): ResponseMap
    {
        $this->beatMap = $beatMap;
        return $this;
    }
}