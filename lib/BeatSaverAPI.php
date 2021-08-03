<?php

namespace KriKrixs;

class BeatSaverAPI
{
    const BEATSAVER_URL = "https://api.beatmaps.io/"; // Might Change when BeatSaver is up
    const MAPS_NUMBERS_PER_PAGE = 20;

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

    /**
     * Private building response functions
     * @param string $endpoint
     * @return array
     */
    private function get(string $endpoint): array
    {
        $apiResult = $this->callAPI($endpoint);

        return [
            "error" => $apiResult === false,
            "map" => json_decode($apiResult, true)
        ];
    }

    ///////////////
    /// Get Map ///
    ///////////////

    /**
     * Get map by ID
     * @param int $id
     * @return array
     */
    public function getMapByID(int $id): array
    {
        return $this->get("/maps/id/" . $id);
    }

    /**
     * Get map by BSR Key
     * @param string $bsrKey BSR Key of the map
     * @return array
     */
    public function getMapByKey(string $bsrKey): array
    {
        return $this->get("/maps/beatsaver/" . $bsrKey);
    }

    /**
     * Get map by Hash
     * @param string $hash Hash of the map
     * @return array
     */
    public function getMapByHash(string $hash): array
    {
        return $this->get("/maps/hash/" . $hash);
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

                if(count($apiResult["docs"]) === 0)
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

            if(count($apiResult["docs"]) < ($page + 1) * self::MAPS_NUMBERS_PER_PAGE)
                break;
        }

        return $response;
    }

    /**
     * Get maps by Uploader ID! Not the uploader name!
     * @param int $uploaderID Uploader ID on BeatSaver
     * @param int $limit How many maps do you want to be returned
     * @return array
     */
    public function getMapsByUploaderID(int $uploaderID, int $limit): array
    {
        return $this->getMaps("/maps/uploader/" . $uploaderID . "/page", $limit);
    }

    /**
     * Get 20 latest maps
     * @param bool $autoMapper Do you want automapper or not ?
     * @return array
     */
    public function getMapsSortedByLatest(bool $autoMapper): array
    {
        return $this->getMaps("/maps/latest?automapper=" . $autoMapper, self::MAPS_NUMBERS_PER_PAGE);
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
     * Search a map (Set null to a parameter to not use it)
     * @param int $limit Limit of map you want
     * @param int $sortOrder (Default 1) 1 = Latest | 2 = Relevance | 3 = Rating
     * @param string|null $mapName (Optional) Map name
     * @param \DateTime|null $startDate (Optional) Map made from this date
     * @param \DateTime|null $endDate (Optional) Map made to this date
     * @param bool $ranked (Optional) Want ranked or not ?
     * @param bool $automapper (Optional) Want automapper or not ?
     * @param bool $chroma (Optional) Want chroma or not ?
     * @param bool $noodle (Optional) Want noodle or not ?
     * @param bool $cinema (Optional) Want cinema or not ?
     * @param bool $fullSpread (Optional) Want fullSpread or not ?
     * @param float|null $minBpm (Optional) Minimum BPM
     * @param float|null $maxBpm (Optional) Maximum BPM
     * @param float|null $minNps (Optional) Minimum NPS
     * @param float|null $maxNps (Optional) Maximum NPS
     * @param float|null $minRating (Optional) Minimum Rating
     * @param float|null $maxRating (Optional) Maximum Rating
     * @param int|null $minDuration (Optional) Minimum Duration
     * @param int|null $maxDuration (Optional) Maximum Duration
     * @return array
     */
    public function searchMap(int $limit, int $sortOrder = 1, string $mapName = null, \DateTime $startDate = null, \DateTime $endDate = null, bool $ranked = false, bool $automapper = false, bool $chroma = false, bool $noodle = false, bool $cinema = false, bool $fullSpread = false, float $minBpm = null, float $maxBpm = null, float $minNps = null, float $maxNps = null, float $minRating = null, float $maxRating = null, int $minDuration = null, int $maxDuration = null): array
    {
        $sort = [
            1 => "Latest",
            2 => "Relevance",
            3 => "Rating"
        ];

        $endpoint = "/search/text/page?sortOrder=" . $sort[$sortOrder];

        if($mapName)        $endpoint .= "&q=" . urlencode($mapName);
        if($startDate)      $endpoint .= "&from=" . $startDate->format("Y-m-d");
        if($endDate)        $endpoint .= "&to=" . $endDate->format("Y-m-d");
        if($ranked)         $endpoint .= "&ranked=" . /** @scrutinizer ignore-type */ $ranked;
        if($automapper)     $endpoint .= "&automapper=" . /** @scrutinizer ignore-type */ $automapper;
        if($chroma)         $endpoint .= "&chroma=" . /** @scrutinizer ignore-type */ $chroma;
        if($noodle)         $endpoint .= "&noodle=" . /** @scrutinizer ignore-type */ $noodle;
        if($cinema)         $endpoint .= "&cinema=" . /** @scrutinizer ignore-type */ $cinema;
        if($fullSpread)     $endpoint .= "&fullSpread=" . /** @scrutinizer ignore-type */ $fullSpread;
        if($minBpm)         $endpoint .= "&minBpm=" . /** @scrutinizer ignore-type */ $minBpm;
        if($maxBpm)         $endpoint .= "&maxBpm=" . /** @scrutinizer ignore-type */ $maxBpm;
        if($minNps)         $endpoint .= "&minNps=" . /** @scrutinizer ignore-type */ $minNps;
        if($maxNps)         $endpoint .= "&maxNps=" . /** @scrutinizer ignore-type */ $maxNps;
        if($minRating)      $endpoint .= "&minRating=" . /** @scrutinizer ignore-type */ $minRating;
        if($maxRating)      $endpoint .= "&maxRating=" . /** @scrutinizer ignore-type */ $maxRating;
        if($minDuration !== null)    $endpoint .= "&minDuration=" . /** @scrutinizer ignore-type */ $minDuration;
        if($maxDuration !== null)    $endpoint .= "&maxDuration=" . /** @scrutinizer ignore-type */ $maxDuration;

        return $this->getMaps($endpoint, $limit);
    }

    ////////////////
    /// Get User ///
    ////////////////

    /**
     * Get user's infos by UserID
     * @param int $id User ID
     * @return array
     */
    public function getUserByID(int $id): array
    {
        return $this->get("/users/id/" . $id);
    }
}