<?php

namespace App\v1\Models;

use JsonSerializable;

final class UserRouteModel implements JsonSerializable
{
    private $userRouteId = 0;
    private $userId = 0;
    private $userRouteDuration = '';
    private $userRouteDistance = '';
    private $userRouteCalories = '';
    private $userRouteRhythm = '';
    private $userRouteSpeed = '';
    private $userRouteDescription = '';
    private $userRouteStartTime = '';
    private $userRouteEndTime = '';

    public function jsonSerialize()
    {
        return [
            'userRouteId' => $this->userRouteId,
            'userId' => $this->userId,
            'userRouteDuration' => $this->userRouteDuration,
            'userRouteDistance' => $this->userRouteDistance,
            'userRouteCalories' => $this->userRouteCalories,
            'userRouteRhythm' => $this->userRouteRhythm,
            'userRouteSpeed' => $this->userRouteSpeed,
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

    /**
     * Get the value of userRouteDuration
     */
    public function getUserRouteDuration()
    {
        return $this->userRouteDuration;
    }

    /**
     * Set the value of userRouteDuration
     *
     * @return  self
     */
    public function setUserRouteDuration($userRouteDuration)
    {
        $this->userRouteDuration = $userRouteDuration;

        return $this;
    }

    /**
     * Get the value of userRouteDistance
     */
    public function getUserRouteDistance()
    {
        return $this->userRouteDistance;
    }

    /**
     * Set the value of userRouteDistance
     *
     * @return  self
     */
    public function setUserRouteDistance($userRouteDistance)
    {
        $this->userRouteDistance = $userRouteDistance;

        return $this;
    }

    /**
     * Get the value of userRouteCalories
     */
    public function getUserRouteCalories()
    {
        return $this->userRouteCalories;
    }

    /**
     * Set the value of userRouteCalories
     *
     * @return  self
     */
    public function setUserRouteCalories($userRouteCalories)
    {
        $this->userRouteCalories = $userRouteCalories;

        return $this;
    }

    /**
     * Get the value of userRouteRhythm
     */
    public function getUserRouteRhythm()
    {
        return $this->userRouteRhythm;
    }

    /**
     * Set the value of userRouteRhythm
     *
     * @return  self
     */
    public function setUserRouteRhythm($userRouteRhythm)
    {
        $this->userRouteRhythm = $userRouteRhythm;

        return $this;
    }

    /**
     * Get the value of userRouteSpeed
     */
    public function getUserRouteSpeed()
    {
        return $this->userRouteSpeed;
    }

    /**
     * Set the value of userRouteSpeed
     *
     * @return  self
     */
    public function setUserRouteSpeed($userRouteSpeed)
    {
        $this->userRouteSpeed = $userRouteSpeed;

        return $this;
    }
}
