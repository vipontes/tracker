<?php

namespace App\v1\Models;

use JsonSerializable;

final class UserRouteModel implements JsonSerializable
{
    private $userRouteId = 0;
    private $userId = 0;
    private $userRouteDescription = '';
    private $userRouteStartTime = '';
    private $userRouteEndTime = '';

    public function jsonSerialize()
    {
        return [
            'userRouteId' => $this->userRouteId,
            'userId' => $this->userId,
            'userRouteDescription' => $this->userRouteDescription,
            'userRouteStartTime' => $this->userRouteStartTime,
            'userRouteEndTime' => $this->userRouteEndTime,
        ];
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
     * Get the value of userId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     *
     * @return  self
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of userRouteDescription
     */
    public function getUserRouteDescription()
    {
        return $this->userRouteDescription;
    }

    /**
     * Set the value of userRouteDescription
     *
     * @return  self
     */
    public function setUserRouteDescription($userRouteDescription)
    {
        $this->userRouteDescription = $userRouteDescription;

        return $this;
    }

    /**
     * Get the value of userRouteStartTime
     */
    public function getUserRouteStartTime()
    {
        return $this->userRouteStartTime;
    }

    /**
     * Set the value of userRouteStartTime
     *
     * @return  self
     */
    public function setUserRouteStartTime($userRouteStartTime)
    {
        $this->userRouteStartTime = $userRouteStartTime;

        return $this;
    }

    /**
     * Get the value of userRouteEndTime
     */
    public function getUserRouteEndTime()
    {
        return $this->userRouteEndTime;
    }

    /**
     * Set the value of userRouteEndTime
     *
     * @return  self
     */
    public function setUserRouteEndTime($userRouteEndTime)
    {
        $this->userRouteEndTime = $userRouteEndTime;

        return $this;
    }
}
