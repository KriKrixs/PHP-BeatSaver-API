<?php

namespace KriKrixs\functions;

use KriKrixs\object\beatmap\BeatMap;
use KriKrixs\object\response\ResponseDownload;

/**
 * THIS CODE IS FROM WALK THOSE BRACKETS AND WAS MAINLY MADE BY HARDCPP
 */
class MultiQuery {
    private string $apiUrl;
    private string $userAgent;

    /**
     * MultiQuery Constructor
     * @param string $apiUrl
     * @param string $userAgent
     */
    public function __construct(string $apiUrl, string $userAgent)
    {
        $this->apiUrl       = $apiUrl;
        $this->userAgent    = $userAgent;
    }

    /**
     * @param $p_URLs
     * @return array
     */
    public function DoMultiQuery($p_URLs, bool $isHash): array
    {
        $l_Multi    = curl_multi_init();
        $apiUrlExt  = $isHash ? "/maps/hash/" : "/maps/id/";
        $l_Handles  = [];
        foreach ($p_URLs as $l_URL)
        {
            $l_CURL = curl_init($this->apiUrl.$apiUrlExt.$l_URL);
            curl_setopt($l_CURL, CURLOPT_USERAGENT, $this->userAgent);
            curl_setopt($l_CURL, CURLOPT_RETURNTRANSFER, true);

            $l_Handles[] = $l_CURL;
        }

        foreach ($l_Handles as $l_Current)
            curl_multi_add_handle($l_Multi, $l_Current);

        $l_RunningHandles = null;

        do
        {
            curl_multi_exec($l_Multi, $l_RunningHandles);
            curl_multi_select($l_Multi);
        } while ($l_RunningHandles > 0);

        $l_Result = [];
        foreach ($l_Handles as $l_Current)
        {
            $l_Error = curl_error($l_Current);

            if (!empty($l_Error) || curl_getinfo($l_Current, CURLINFO_HTTP_CODE) !== 200)
                $l_Result[] = false;
            else
                $l_Result[] = new BeatMap(json_decode(curl_multi_getcontent($l_Current)));

            curl_multi_remove_handle($l_Multi, $l_Current);
        }

        curl_multi_close($l_Multi);
        return $l_Result;
    }

    public function downloadMapZipAndCover(array $p_URLs, string $targetDir): ResponseDownload
    {
        $response = new ResponseDownload();

        $anyError = [];
        $allFailed = null;

        foreach ($p_URLs as $hash => $l_URLs) {
            echo $hash . ": ";

            $error = false;

            foreach ($l_URLs as $type => $l_URL) {
                echo $type . ": ";

                $extension = $type === "map" ? '.zip' : '.jpg';

                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                if(substr($targetDir, -1) !== "/")
                    $targetDir .= "/";

                //The path & filename to save to.
                $saveTo = $targetDir . $hash . $extension;

                $ch = curl_init($l_URL);
                curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_ENCODING, "");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

                $result = curl_exec($ch);

                if(curl_errno($ch) === 0) {
                    $allFailed = false;
                    echo "Ok ";
                } else {
                    $anyError[] = $hash;
                    $error = true;
                    if(is_null($allFailed)) $allFailed = true;
                    echo "Error ";
                }

                file_put_contents($saveTo, $result);

                curl_close($ch);
            }

            echo "save: " . ($error ? "No" : "Yes") . "\n";
        }

        $status = "";

        if($allFailed) {
            $status = "All failed";
            $response->setErrorStatus(true)->setErrorMessage("Can't download all/some maps");
        } else {
            if(count($anyError) !== 0) {
                $response->setErrorStatus(true)->setErrorMessage("Can't download all/some maps");

                foreach ($anyError as $hash) {
                    $status .= $hash . ", ";
                }

                $status .= "Failed";
            }
        }

        $response->setDownloadStatus($status);

        return $response;
    }

    public function buildDownloadArray($items, $isHash): array
    {
        $downloadLinks = [];

        /** @var BeatMap $beatmap */
        foreach ($this->DoMultiQuery($items, $isHash) as $beatmap) {
            $downloadLinks[$beatmap->getVersions()[0]->getHash()]["map"] = $beatmap->getVersions()[0]->getDownloadURL();
            $downloadLinks[$beatmap->getVersions()[0]->getHash()]["cover"] = $beatmap->getVersions()[0]->getCoverURL();
        }

        return $downloadLinks;
    }

}