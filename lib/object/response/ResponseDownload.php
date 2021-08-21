<?php

namespace KriKrixs\object\response;

use KriKrixs\object\User\User;

class ResponseDownload extends Response
{
    private array $downloadedMaps   = [];
    private array $failedMaps       = [];

    /**
     * Get downloaded maps hash
     * @return array Array of hash
     */
    public function getDownloadedMapsHash(): array
    {
        return $this->downloadedMaps;
    }

    /**
     * Get failed maps hash
     * @return array Array of hash
     */
    public function getFailedMapsHash(): array
    {
        return $this->failedMaps;
    }

    /**
     * Push a new hash in the downloadedMaps array
     * @param string $hash Map hash
     * @return ResponseDownload
     */
    public function pushDownloadMapHash(string $hash): ResponseDownload
    {
        $this->downloadedMaps[] = $hash;
        return $this;
    }

    /**
     * Push a new hash in the failedMaps array
     * @param string $hash Map hash
     * @return ResponseDownload
     */
    public function pushFailMapHash(string $hash): ResponseDownload
    {
        $this->failedMaps[] = $hash;
        return $this;
    }
}