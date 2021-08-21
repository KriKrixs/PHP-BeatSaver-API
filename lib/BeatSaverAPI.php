<?php

namespace KriKrixs;

use KriKrixs\functions\MultiQuery;
use KriKrixs\object\beatmap\BeatMap;
use KriKrixs\object\response\ResponseDownload;
use KriKrixs\object\response\ResponseMap;
use KriKrixs\object\response\ResponseMaps;
use KriKrixs\object\response\ResponseUser;
use KriKrixs\object\user\User;

class BeatSaverAPI
{
    const BEATSAVER_URL = "https://api.beatsaver.com/";

    private string $userAgent;
    private MultiQuery $multiQuery;

    /**
     * BeatSaverAPI constructor
     * @param string $userAgent User Agent to provide to Beat Saver API
     */
    public function __construct(string $userAgent)
    {
        $this->autoload("./");

        $this->userAgent = $userAgent;
        $this->multiQuery = new MultiQuery(self::BEATSAVER_URL, $userAgent);
    }

    private function autoload($directory) {
        if(is_dir($directory)) {
            $scan = scandir($directory);
            unset($scan[0], $scan[1]); //unset . and ..
            foreach($scan as $file) {
                if(is_dir($directory."/".$file)) {
                    $this->autoload($directory."/".$file);
                } else {
                    if(strpos($file, '.php') !== false) {
                        include_once($directory."/".$file);
                    }
                }
            }
        }
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
     * @return ResponseMap
     */
    private function getMap(string $endpoint): ResponseMap
    {
        $response = new ResponseMap();

        $apiResult = $this->callAPI($endpoint);

        if($apiResult === false || $apiResult == "Not Found") {
            $response->setErrorStatus(true)->setErrorMessage("[getMap] Something went wrong with the API call.");
            return $response;
        }

        $response->setBeatMap(new BeatMap(json_decode($apiResult)));

        return $response;
    }

    ///////////////
    /// Get Map ///
    ///////////////

    /**
     * Get map by ID (Same as BSR Key)
     * @param string $id Map ID
     * @return ResponseMap
     */
    public function getMapByID(string $id): ResponseMap
    {
        return $this->getMap("/maps/id/" . $id);
    }

    /**
     * Get map by BSR Key (Same as ID)
     * @param string $bsrKey Map BSR key
     * @return ResponseMap
     */
    public function getMapByKey(string $bsrKey): ResponseMap
    {
        return $this->getMap("/maps/id/" . $bsrKey);
    }

    /**
     * Get map by Hash
     * @param string $hash Hash of the map
     * @return ResponseMap
     */
    public function getMapByHash(string $hash): ResponseMap
    {
        return $this->getMap("/maps/hash/" . $hash);
    }

    ////////////////
    /// Get Maps ///
    ////////////////

    /**
     * Private building response functions
     * @param string $endpoint
     * @param int $numberOfPage
     * @param int $startPage
     * @return ResponseMaps
     */
    private function getMaps(string $endpoint, int $numberOfPage = 0, int $startPage = 0): ResponseMaps
    {
        $response = new ResponseMaps();
        $maps = [];

        // Latest
        if($numberOfPage === 0 && $startPage === 0){
            $apiResult = json_decode($this->callAPI($endpoint));

            if($apiResult === false || $apiResult == "Not Found") {
                $response->setErrorStatus(true)->setErrorMessage("[getMaps] Something went wrong with the API call while calling the first page.");
                return $response;
            } else{
                foreach ($apiResult->docs as $beatmap) {
                    $maps[] = new BeatMap($beatmap);
                }
            }
        } else {
            for($i = $startPage; $i < ($i + $numberOfPage); $i++){
                $apiResult = json_decode($this->callAPI(str_ireplace("page", $i, $endpoint)));

                if($apiResult === false || $apiResult == "Not Found") {
                    $response->setErrorStatus(true)->setErrorMessage("[getMaps] Something went wrong with the API call while calling page number " . $i . ".");

                    if($apiResult == "Not Found")
                        return $response;
                }

                foreach ($apiResult->docs as $beatmap) {
                    $maps[] = new BeatMap($beatmap);
                }
            }
        }

        $response->setBeatMaps($maps);

        return $response;
    }

    /**
     * Get maps by Uploader ID! Not the uploader name!
     * @param int $uploaderID Uploader ID on BeatSaver
     * @param int $numberOfPage The number of page you want to be returned
     * @param int $startPage The starting page
     * @return ResponseMaps
     */
    public function getMapsByUploaderID(int $uploaderID, int $numberOfPage, int $startPage): ResponseMaps
    {
        return $this->getMaps("/maps/uploader/" . $uploaderID . "/page", $numberOfPage, $startPage);
    }

    /**
     * Get 20 latest maps
     * @param bool $autoMapper Do you want automapper or not ?
     * @return ResponseMaps
     */
    public function getMapsSortedByLatest(bool $autoMapper): ResponseMaps
    {
        return $this->getMaps("/maps/latest?automapper=" . var_export($autoMapper, true));
    }

    /**
     * Get maps sorted by plays numbers
     * @param int $numberOfPage The number of page you want to be returned
     * @param int $startPage The starting page
     * @return ResponseMaps
     */
    public function getMapsSortedByPlays(int $numberOfPage, int $startPage): ResponseMaps
    {
        return $this->getMaps("/maps/plays/page", $numberOfPage, $startPage);
    }

    /**
     * Search a map (Set null to a parameter to not use it)
     * @param int $startPage Start page number
     * @param int $numberOfPage Number of page wanted
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
     * @return ResponseMaps
     */
    public function searchMap(int $startPage = 0, int $numberOfPage = 1, int $sortOrder = 1, string $mapName = null, \DateTime $startDate = null, \DateTime $endDate = null, bool $ranked = false, bool $automapper = false, bool $chroma = false, bool $noodle = false, bool $cinema = false, bool $fullSpread = false, float $minBpm = null, float $maxBpm = null, float $minNps = null, float $maxNps = null, float $minRating = null, float $maxRating = null, int $minDuration = null, int $maxDuration = null): ResponseMaps
    {
        $sort = [
            1 => "Latest",
            2 => "Relevance",
            3 => "Rating"
        ];

        $endpoint = "/search/text/page?sortOrder=" . $sort[$sortOrder];

        if($mapName)                $endpoint .= "&q=" . urlencode($mapName);
        if($startDate)              $endpoint .= "&from=" . $startDate->format("Y-m-d");
        if($endDate)                $endpoint .= "&to=" . $endDate->format("Y-m-d");
        if($ranked)                 $endpoint .= "&ranked=" . /** @scrutinizer ignore-type */ var_export($ranked, true);
        if($automapper)             $endpoint .= "&automapper=" . /** @scrutinizer ignore-type */ var_export($automapper, true);
        if($chroma)                 $endpoint .= "&chroma=" . /** @scrutinizer ignore-type */ var_export($chroma, true);
        if($noodle)                 $endpoint .= "&noodle=" . /** @scrutinizer ignore-type */ var_export($noodle, true);
        if($cinema)                 $endpoint .= "&cinema=" . /** @scrutinizer ignore-type */ var_export($cinema, true);
        if($fullSpread)             $endpoint .= "&fullSpread=" . /** @scrutinizer ignore-type */ var_export($fullSpread, true);
        if($minBpm)                 $endpoint .= "&minBpm=" . /** @scrutinizer ignore-type */ $minBpm;
        if($maxBpm)                 $endpoint .= "&maxBpm=" . /** @scrutinizer ignore-type */ $maxBpm;
        if($minNps)                 $endpoint .= "&minNps=" . /** @scrutinizer ignore-type */ $minNps;
        if($maxNps)                 $endpoint .= "&maxNps=" . /** @scrutinizer ignore-type */ $maxNps;
        if($minRating)              $endpoint .= "&minRating=" . /** @scrutinizer ignore-type */ $minRating;
        if($maxRating)              $endpoint .= "&maxRating=" . /** @scrutinizer ignore-type */ $maxRating;
        if($minDuration !== null)   $endpoint .= "&minDuration=" . /** @scrutinizer ignore-type */ $minDuration;
        if($maxDuration !== null)   $endpoint .= "&maxDuration=" . /** @scrutinizer ignore-type */ $maxDuration;

        return $this->getMaps($endpoint, $numberOfPage, $startPage);
    }

    ////////////////
    /// Get User ///
    ////////////////

    /**
     * Private building response functions
     * @param string $endpoint
     * @return ResponseUser
     */
    private function getUser(string $endpoint): ResponseUser
    {
        $response = new ResponseUser();

        $apiResult = $this->callAPI($endpoint);

        if($apiResult === false || $apiResult == "Not Found") {
            $response->setErrorStatus(true)->setErrorMessage("[getMap] Something went wrong with the API call.");
            return $response;
        }

        $response->setUser(new User(json_decode($apiResult)));

        return $response;
    }

    /**
     * Get user's infos by UserID
     * @param int $id User ID
     * @return ResponseUser
     */
    public function getUserByID(int $id): ResponseUser
    {
        return $this->getUser("/users/id/" . $id);
    }

    ////////////////////
    /// Download map ///
    ////////////////////

    /**
     * Download maps using id
     * @param array $ids
     * @param string $targetDir
     * @return ResponseDownload
     */
    public function downloadMapByIds(array $ids, string $targetDir): ResponseDownload
    {
        return $this->multiQuery->downloadMapZipAndCover($this->multiQuery->buildDownloadArray($ids, false), $targetDir);
    }

    /**
     * Download maps using hashes
     * @param array $hashes
     * @param string $targetDir
     * @return ResponseDownload
     */
    public function downloadMapByHashes(array $hashes, string $targetDir): ResponseDownload
    {
        return $this->multiQuery->downloadMapZipAndCover($this->multiQuery->buildDownloadArray($hashes, true), $targetDir);
    }
}