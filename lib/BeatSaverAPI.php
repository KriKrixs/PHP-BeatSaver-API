<?php

namespace KriKrixs;

class BeatSaverAPI
{
    const BEATSAVER_URL = "https://beatsaver.com/api";
    const MAPS_NUMBERS_PER_PAGE = 25;

    private string $userAgent;

    /**
     * BeatSaverAPI constructor
     * @param string $userAgent User Agent to provide to Beat Saver API
     */
    public function __construct(string $userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * Private calling API function
     * @param string $endpoint
     * @return string|null
     */
    private function callAPI(string $endpoint): ?string
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => self::BEATSAVER_URL . $endpoint,
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_RETURNTRANSFER => true
        ]);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    ////////////
    /// CALL ///
    ///////////

    ///////////////
    /// Get Map ///
    ///////////////

    /**
     * Private building response functions
     * @param string $endpoint
     * @return array
     */
    private function getMap(string $endpoint): array
    {
        $apiResult = $this->callAPI($endpoint);

        return [
            "error" => $apiResult === false,
            "map" => json_decode($apiResult, true)
        ];
    }

    /**
     * Get map by BSR Key
     * @param string $bsrKey BSR Key of the map
     * @return array
     */
    public function getMapByKey(string $bsrKey): array
    {
        return $this->getMap("/maps/detail/" . $bsrKey);
    }

    /**
     * Get map by Hash
     * @param string $hash Hash of the map
     * @return array
     */
    public function getMapByHash(string $hash): array
    {
        return $this->getMap("/maps/by-hash/" . $hash);
    }

    ////////////////
    /// Get Maps ///
    ////////////////

    /**
     * Private building response functions
     * @param string $endpoint
     * @param int $limit
     * @return array
     */
    private function getMaps(string $endpoint, int $limit): array
    {
        $response = [
            "error" => false,
            "maps" => []
        ];

        for($page = 0; $page <= floor(($limit - 1) / self::MAPS_NUMBERS_PER_PAGE); $page++) {
            $apiResult = $this->callAPI(str_ireplace("page", $page, $endpoint));

            if($apiResult === false || $apiResult == "Not Found") {
                $response["error"] = true;

                if($apiResult == "Not Found")
                    break;
            } else {
                $apiResult = json_decode($apiResult, true);

                if($apiResult["totalDocs"] === 0)
                    break;

                if(($page + 1) * self::MAPS_NUMBERS_PER_PAGE <= $limit) {
                    $response["maps"] = array_merge($response["maps"], $apiResult["docs"]);
                } else {
                    $max = $limit <= self::MAPS_NUMBERS_PER_PAGE ? $limit : $limit - ($page * self::MAPS_NUMBERS_PER_PAGE);

                    for($i = 0; $i < $max; $i++) {
                        array_push($response["maps"], $apiResult["docs"][$i]);
                    }
                }
            }

            if($apiResult["totalDocs"] < ($page + 1) * self::MAPS_NUMBERS_PER_PAGE)
                break;
        }

        return $response;
    }

    /**
     * Get maps by Uploader ID! Not the uploader name!
     * @param string $uploader Uploader username on BeatSaver
     * @param int $limit How many maps do you want to be returned
     * @return string|bool
     */
    public function getMapsByUploaderID(string $uploader, int $limit): array
    {
        return $this->getMaps("/maps/uploader/" . urlencode($uploader) . "/page", $limit);
    }

    /**
     * Get maps sorted by downloads numbers
     * @param int $limit How many maps do you want to be returned
     * @return array
     */
    public function getMapsSortedByDownloads(int $limit): array
    {
        return $this->getMaps("/maps/downloads/page", $limit);
    }

    /**
     * Get maps sorted by Hot
     * @param int $limit How many maps do you want to be returned
     * @return array
     */
    public function getMapsSortedByHot(int $limit): array
    {
        return $this->getMaps("/maps/hot/page", $limit);
    }

    /**
     * Get latest maps
     * @param int $limit How many maps do you want to be returned
     * @return array
     */
    public function getMapsSortedByLatest(int $limit): array
    {
        return $this->getMaps("/maps/latest/page", $limit);
    }

    /**
     * Get maps sorted by plays numbers
     * @param int $limit How many maps do you want to be returned
     * @return array
     */
    public function getMapsSortedByPlays(int $limit): array
    {
        return $this->getMaps("/maps/plays/page", $limit);
    }

    /**
     * Get maps sorted by their rating
     * @param int $limit How many maps do you want to be returned
     * @return array
     */
    public function getMapsSortedByRating(int $limit): array
    {
        return $this->getMaps("/maps/rating/page", $limit);
    }

    /**
     * Get maps by the name
     * @param string $mapName Map name
     * @param int $limit How many maps do you want to be returned
     * @return array
     */
    public function getMapsByName(string $mapName, int $limit): array
    {
        return $this->getMaps("/search/text/page?q=" . urlencode($mapName), $limit);
    }
}