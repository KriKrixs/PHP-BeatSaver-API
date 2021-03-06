<?php

namespace KriKrixs;

use KriKrixs\object\beatmap\BeatMap;
use KriKrixs\object\response\ResponseDownload;
use KriKrixs\object\response\ResponseMap;
use KriKrixs\object\response\ResponseMaps;
use KriKrixs\object\response\ResponseUser;
use KriKrixs\object\user\User;

class BeatSaverAPI
{
    const BEATSAVER_URL     = "https://api.beatsaver.com/";
    const BEATSAVER_CDN_URL = "https://cdn.beatsaver.com/";
    const MAX_HASHES_NUMBER = 50;
    const MAX_CALL_PER_SECS = 10;

    private string $userAgent;

    /**
     * BeatSaverAPI constructor
     * @param string $userAgent User Agent to provide to Beat Saver API
     * @param bool $needAutoloader If you don't use it with composer = true
     */
    public function __construct(string $userAgent, bool $needAutoloader = false)
    {
        if($needAutoloader)
            $this->autoload("./");

        $this->userAgent = $userAgent;
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

        if($apiResult === false) {
            $response->setErrorStatus(true)->setErrorMessage("[getMap] Something went wrong with the API call (" . $endpoint . ")");
            return $response;
        } elseif((json_decode($apiResult))->error == "Not Found") {
            $response->setErrorStatus(true)->setErrorMessage("[getMap] Map not found (" . $endpoint . ")");
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
     * @return ResponseMaps
     */
    private function getMaps(string $endpoint): ResponseMaps
    {
        $response = new ResponseMaps();

        $apiResult = $this->callAPI($endpoint);

        if($apiResult === false || $apiResult == "Not Found") {
            $response->setErrorStatus(true)->setErrorMessage("[getMap] Something went wrong with the API call.");
            return $response;
        }

        $response->setRawBeatMaps(json_decode($apiResult));

        return $response;
    }

    /**
     * Get maps by IDs (Same as BSR keys)
     * @param array $ids Array of maps ID (Same as BSR keys)
     * @return array Array of BeatMap object
     * @deprecated This function may end up with a 429 - Too many request. Use getMapsByHashes instead to avoid it.
     */
//    public function getMapsByIds(array $ids): array
//    {
//        return $this->multiQuery->DoMultiQuery($ids, false);
//    }

    /**
     * Get maps by BSR Keys (Same as IDs)
     * @param array $keys Array of maps BSR key (Same as IDs)
     * @return array Array of BeatMap object
     * @deprecated This function may end up with a 429 - Too many request. Use getMapsByHashes instead to avoid it.
     */
//    public function getMapsByKeys(array $keys): array
//    {
//        return $this->multiQuery->DoMultiQuery($keys, false);
//    }

    /**
     * Get maps by hashes
     * @param array $hashes Array of maps hash (minimum 2 hash)
     * @return ResponseMaps Array of BeatMap object
     */
    public function getMapsByHashes(array $hashes): ResponseMaps
    {
        $endpoint = "/maps/hash/";
        $hashesString = $endpoint;
        $mapsArray = [];
        $i = 0;
        $callNumber = 0;
        $result = new ResponseMaps();

        if(count($hashes) < 2) {
            return $result->setErrorStatus(true)->setErrorMessage("This functions require a minimum of 2 hashes in the array");
        }

        foreach($hashes as $hash) {
            $hashesString .= $hash;

            if($i !== 0 && $i % self::MAX_HASHES_NUMBER === 0) {
                if($callNumber === self::MAX_CALL_PER_SECS) {
                    sleep(1);
                    $callNumber = 0;
                }

                $maps = $this->getMaps($hashesString);
                $callNumber++;

                $mapsArray = array_merge($mapsArray, $maps->getBeatMaps());

                if(!isset($mapsArray["errorStatus"]) || !$mapsArray["errorStatus"]) {
                    $mapsArray["errorStatus"] = $maps->getErrorStatus();
                    $mapsArray["errorMessage"] = $maps->getErrorMessage();
                }

                $hashesString = $endpoint;
            } else {
                $hashesString .= ",";
            }

            $i++;
        }


        if($i !== 0) {
            $maps = $this->getMaps(substr($hashesString, 0, -1));
            $mapsArray = array_merge($mapsArray, $maps->getBeatMaps());

            if(!isset($mapsArray["errorStatus"]) || !$mapsArray["errorStatus"]) {
                $mapsArray["errorStatus"] = $maps->getErrorStatus();
                $mapsArray["errorMessage"] = $maps->getErrorMessage();
            }
        }

        if(isset($mapsArray["errorStatus"]) && $mapsArray["errorStatus"])
            $result->setErrorStatus( $mapsArray["errorStatus"])->setErrorMessage( $mapsArray["errorMessage"]);

        unset($mapsArray["errorStatus"]);
        unset($mapsArray["errorMessage"]);

        return $result->setBeatMaps($mapsArray);
    }

    /**
     * Private building response functions
     * @param string $endpoint
     * @param int $numberOfPage
     * @param int $startPage
     * @return ResponseMaps
     */
    private function getMapsByEndpoint(string $endpoint, int $numberOfPage = 0, int $startPage = 0): ResponseMaps
    {
        $response = new ResponseMaps();
        $maps = [];
        $callNumber = 0;

        // Latest
        if($numberOfPage === 0 && $startPage === 0){
            $apiResult = json_decode($this->callAPI(str_ireplace("page", 0, $endpoint)));

            if($apiResult === false || $apiResult == "Not Found") {
                $response->setErrorStatus(true)->setErrorMessage("[getMaps] Something went wrong with the API call while calling the first page.");
                return $response;
            } else{
                foreach ($apiResult->docs as $beatmap) {
                    $maps[] = new BeatMap($beatmap);
                }
            }
        } else {
            for($i = $startPage; $i < ($startPage + $numberOfPage); $i++){
                if($callNumber === self::MAX_CALL_PER_SECS) {
                    sleep(1);
                    $callNumber = 0;
                }

                $apiResult = json_decode($this->callAPI(str_ireplace("page", $i, $endpoint)));
                $callNumber++;

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
    public function getMapsByUploaderID(int $uploaderID, int $numberOfPage = 0, int $startPage = 0): ResponseMaps
    {
        return $this->getMapsByEndpoint("/maps/uploader/" . $uploaderID . "/page", $numberOfPage, $startPage);
    }

    /**
     * Get 20 latest maps
     * @param bool $autoMapper Do you want automapper or not ?
     * @return ResponseMaps
     */
    public function getMapsSortedByLatest(bool $autoMapper): ResponseMaps
    {
        return $this->getMapsByEndpoint("/maps/latest?automapper=" . var_export($autoMapper, true));
    }

    /**
     * Get maps sorted by plays numbers
     * @param int $numberOfPage The number of page you want to be returned
     * @param int $startPage The starting page
     * @return ResponseMaps
     */
    public function getMapsSortedByPlays(int $numberOfPage = 0, int $startPage = 0): ResponseMaps
    {
        return $this->getMapsByEndpoint("/maps/plays/page", $numberOfPage, $startPage);
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

        return $this->getMapsByEndpoint($endpoint, $numberOfPage, $startPage);
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

    private function downloadMapZipAndCover(array $hashes, string $targetDir): ResponseDownload
    {
        $response = new ResponseDownload();

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if(substr($targetDir, -1) !== "/")
            $targetDir .= "/";

        foreach ($hashes as $hash) {
            //The path & filename to save to.
            $saveTo = $targetDir . $hash;

            echo $hash . ": ";

            $error = false;

            echo "map" . ": ";

            $ch = curl_init(self::BEATSAVER_CDN_URL . $hash . ".zip");
            curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

            $result = curl_exec($ch);

            if(curl_errno($ch) === 0) {
                $response->pushDownloadMapHash($hash);
                echo "Ok ";
            } else {
                $response->pushFailMapHash($hash)->setErrorStatus(true)->setErrorMessage("Something went wrong with some maps");
                $error = true;
                echo "Error ";
            }

            file_put_contents($saveTo . ".zip", $result);

            curl_close($ch);

            echo "cover" . ": ";

            $ch = curl_init(self::BEATSAVER_CDN_URL . $hash . ".jpg");
            curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

            $result = curl_exec($ch);

            if(curl_errno($ch) === 0) {
                $response->pushDownloadMapHash($hash);
                echo "Ok ";
            } else {
                $response->pushFailMapHash($hash)->setErrorStatus(true)->setErrorMessage("Something went wrong with some maps");
                $error = true;
                echo "Error ";
            }

            file_put_contents($saveTo . ".jpg", $result);

            curl_close($ch);

            echo "save: " . ($error ? "No" : "Yes") . "\n";
        }

        return $response;
    }

    /**
     * Download maps using id (Same as BSR Key)
     * @param array $ids Array of maps IDs (Same as BSR Key)
     * @param string $targetDir Path to download dir
     * @deprecated Will not work, use downloadMapByHashes instead
     * @return ResponseDownload
     */
    public function downloadMapByIds(array $ids, string $targetDir): ResponseDownload
    {
        return $this->downloadMapZipAndCover($ids, $targetDir);
    }

    /**
     * Download maps using bsr key (Same as ID)
     * @param array $keys Array of maps keys (Same as ID)
     * @param string $targetDir Path to download dir
     * @deprecated Will not work, use downloadMapByHashes instead
     * @return ResponseDownload
     */
    public function downloadMapByKeys(array $keys, string $targetDir): ResponseDownload
    {
        return $this->downloadMapZipAndCover($keys, $targetDir);
    }

    /**
     * Download maps using hashes
     * @param array $hashes Array of maps hashes
     * @param string $targetDir Path to download dir
     * @return ResponseDownload
     */
    public function downloadMapByHashes(array $hashes, string $targetDir): ResponseDownload
    {
        return $this->downloadMapZipAndCover($hashes, $targetDir);
    }
}
