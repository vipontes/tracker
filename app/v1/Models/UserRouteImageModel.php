<?php

namespace App\v1\Models;

use JsonSerializable;

final class UserRouteImageModel implements JsonSerializable
{
    private $userRouteImageId = 0;
    private $userRouteId = 0;
    private $userRouteImageFile = '';
    private $userRouteImageDatetime = '';

    public function jsonSerialize()
    {
        return [
            'userRouteImageId' => $this->userRouteImageId,
            'userRouteId' => $this->userRouteId,
            'userRouteImageLatlng' => $this->userRouteImageLatlng,
            'userRouteImageDatetime' => $this->userRouteImageDatetime,
        ];
    }

    /**
     * Get the value of userRouteImageId
     */
    public function getUserRouteImageId()
    {
        return $this->userRouteImageId;
    }

    /**
     * Set the value of userRouteImageId
     *
     * @return  self
     */
    public function setUserRouteImageId($userRouteImageId)
    {
        $this->userRouteImageId = $userRouteImageId;

        return $this;
    }

    /**
     * Get the value of userRouteId
     */
    public function getUserRouteId()
    {
        return $this->userRouteId;
    }

    /**
     * Set the value of userRouteId
     *
     * @return  self
     */
    public function setUserRouteId($userRouteId)
    {
        $this->userRouteId = $userRouteId;

        return $this;
    }

    /**
     * Get the value of userRouteImageFile
     */
    public function getUserRouteImageFile()
    {
        return $this->userRouteImageFile;
    }

    /**
     * Set the value of userRouteImageFile
     *
     * @return  self
     */
    public function setUserRouteImageFile($userRouteImageFile)
    {
        $this->userRouteImageFile = $userRouteImageFile;

        return $this;
    }

    /**
     * Get the value of userRouteImageDatetime
     */
    public function getUserRouteImageDatetime()
    {
        return $this->userRouteImageDatetime;
    }

    /**
     * Set the value of userRouteImageDatetime
     *
     * @return  self
     */
    public function setUserRouteImageDatetime($userRouteImageDatetime)
    {
        $this->userRouteImageDatetime = $userRouteImageDatetime;

        return $this;
    }
}
