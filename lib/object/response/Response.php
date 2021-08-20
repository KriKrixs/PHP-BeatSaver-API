<?php

namespace KriKrixs\object\response;

use KriKrixs\object\beatmap\BeatMap;

class Response
{
    private bool        $errorStatus    = false;
    private string      $errorMessage   = "";

    /**
     * Get error status
     * @return bool
     */
    public function getErrorStatus(): bool
    {
        return $this->errorStatus;
    }

    /**
     * Get error message
     * @return string
     */
    public function getErrorMessage() :string
    {
        return $this->errorMessage;
    }

    /**
     * set error status
     * @param bool $errorStatus
     * @return Response
     */
    public function setErrorStatus(bool $errorStatus): Response
    {
        $this->errorStatus = $errorStatus;
        return $this;
    }

    /**
     * Set error message
     * @param string $errorMessage
     * @return Response
     */
    public function setErrorMessage(string $errorMessage): Response
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }
}