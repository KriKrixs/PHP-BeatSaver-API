<?php

namespace KriKrixs;

class BeatSaverAPI
{
    const BEATSAVER_URL = "https://beatsaver.com/api";

    private string $userAgent;

    /**
     * @param string $userAgent User Agent to provide to Beat Saver API
     */
    public function __construct(string $userAgent)
    {
        $this->userAgent = $userAgent;
    }

    private function callAPI(string $endpoint)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => self::BEATSAVER_URL . $endpoint,
            CURLOPT_USERAGENT => $this->userAgent
        ]);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    /**
     * CALL
     */

    /**
     * Get map by BSR Key
     * @param string $bsrKey BSR Key of the map
     */
    public function getMapByKey(string $bsrKey)
    {
        return $this->callAPI("/maps/detail/" . $bsrKey);
    }
}
