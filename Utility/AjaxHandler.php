<?php

namespace WPPluginName\Utility;

/**
 * Class AjaxHandler
 * @package WPPluginName\Utility
 */
final class AjaxHandler
{
    /** @var int */
    protected $statusCode;
    /** @var string */
    protected $statusMessage;
    /** @var string */
    protected $additionalMessage;
    /** @var array */
    protected $returnedData = [];

    /**
     * @param bool $die
     */
    public function returnResponse(bool $die = true): void
    {
        $response = [
            'status'=> $this->getStatusCode(),
            'message'=> $this->getStatusMessage()
        ];

        if ($this->getAdditionalMessage()) {
            $response['additionalMessage'] = $this->getAdditionalMessage();
        }

        if (count($this->getReturnedData()) > 0) {
            $response['data'] = $this->getReturnedData();
        }

        echo json_encode($response);

        if ($die) {
            wp_die(); //kills the wp
        }
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return AjaxHandler
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatusMessage(): string
    {
        return $this->statusMessage;
    }

    /**
     * @param string $statusMessage
     * @return AjaxHandler
     */
    public function setStatusMessage(string $statusMessage): self
    {
        $this->statusMessage = $statusMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdditionalMessage(): string
    {
        return $this->additionalMessage;
    }

    /**
     * @param string $additionalMessage
     * @return AjaxHandler
     */
    public function setAdditionalMessage(string $additionalMessage): self
    {
        $this->additionalMessage = $additionalMessage;
        return $this;
    }

    /**
     * @return array
     */
    public function getReturnedData(): array
    {
        return json_encode($this->returnedData);
    }

    /**
     * @param array $returnedData
     * @return AjaxHandler
     */
    public function setReturnedData(array $returnedData): self
    {
        $this->returnedData = $returnedData;
        return $this;
    }
}