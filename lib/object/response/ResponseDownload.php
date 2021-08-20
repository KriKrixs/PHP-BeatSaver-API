<?php

namespace KriKrixs\object\response;

use KriKrixs\object\User\User;

class ResponseDownload extends Response
{
    private string $downloadStatus = "All downloaded";

    /**
     * Get download status
     * @return string
     */
    public function getDownloadStatus(): string
    {
        return $this->downloadStatus;
    }

    /**
     * Set download status
     * @param string $downloadStatus
     * @return ResponseDownload
     */
    public function setDownloadStatus(string $downloadStatus): ResponseDownload
    {
        $this->downloadStatus = $downloadStatus;
        return $this;
    }
}