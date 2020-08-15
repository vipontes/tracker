<?php

namespace App\v1\Models;

use JsonSerializable;

final class UserRoutePathModel implements JsonSerializable
{
    private $userRoutePathId = 0;
    private $userRouteId = 0;
    private $userRoutePathLat = 0;
    private $userRoutePathLng = 0;
    private $userRoutePathAltitude = 0;
    private $userRoutePathDatetime = '';

    public function jsonSerialize()
    {
        return [
            'userRoutePathId' => $this->userRoutePathId,
            'userRouteId' => $this->userRouteId,
            'userRoutePathLat' => $this->userRoutePathLat,
            'userRoutePathLng' => $this->userRoutePathLng,
            'userRoutePathAltitude' => $this->userRoutePathAltitude,
            'userRoutePathDatetime' => $this->userRoutePathDatetime,
        ];
    }

    /**
     * Get the value of userRoutePathId
     */
    public function getUserRoutePathId()
    {
        return $this->userRoutePathId;
    }

    /**
     * Set the value of userRoutePathId
     *
     * @return  self
     */
    public function setUserRoutePathId($userRoutePathId)
    {
        $this->userRoutePathId = $userRoutePathId;

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
     * Get the value of userRoutePathDatetime
     */
    public function getUserRoutePathDatetime()
    {
        return $this->userRoutePathDatetime;
    }

    /**
     * Set the value of userRoutePathDatetime
     *
     * @return  self
     */
    public function setUserRoutePathDatetime($userRoutePathDatetime)
    {
        $this->userRoutePathDatetime = $userRoutePathDatetime;

        return $this;
    }

    /**
     * Get the value of userRoutePathLat
     */
    public function getUserRoutePathLat()
    {
        return $this->userRoutePathLat;
    }

    /**
     * Set the value of userRoutePathLat
     *
     * @return  self
     */
    public function setUserRoutePathLat($userRoutePathLat)
    {
        $this->userRoutePathLat = $userRoutePathLat;

        return $this;
    }

    /**
     * Get the value of userRoutePathLng
     */
    public function getUserRoutePathLng()
    {
        return $this->userRoutePathLng;
    }

    /**
     * Set the value of userRoutePathLng
     *
     * @return  self
     */
    public function setUserRoutePathLng($userRoutePathLng)
    {
        $this->userRoutePathLng = $userRoutePathLng;

        return $this;
    }

    /**
     * Get the value of userRoutePathAltitude
     */
    public function getUserRoutePathAltitude()
    {
        return $this->userRoutePathAltitude;
    }

    /**
     * Set the value of userRoutePathAltitude
     *
     * @return  self
     */
    public function setUserRoutePathAltitude($userRoutePathAltitude)
    {
        $this->userRoutePathAltitude = $userRoutePathAltitude;

        return $this;
    }
}
