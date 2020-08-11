<?php

namespace App\v1\Models;

use JsonSerializable;

final class UserModel implements JsonSerializable
{
    private $userId = 0;
    private $userName = '';
    private $userEmail = '';
    private $userPassword = '';
    private $userActive = 0;
    private $userAvatar = '';
    private $userCreatedAt = '';
    private $userWeight = 0;
    private $token = '';
    private $refreshToken = '';

    public function jsonSerialize()
    {
        return [
            'userId' => $this->userId,
            'userName' => $this->userName,
            'userEmail' => $this->userEmail,
            'userPassword' => $this->userPassword,
            'userActive' => $this->userActive,
            'userAvatar' => $this->userAvatar,
            'userCreatedAt' => $this->userCreatedAt,
            'userWeight' => $this->userWeight,
            'token' => $this->token,
            'refreshToken' => $this->refreshToken,
        ];
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
     * Get the value of userName
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set the value of userName
     *
     * @return  self
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get the value of userEmail
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * Set the value of userEmail
     *
     * @return  self
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    /**
     * Get the value of userPassword
     */
    public function getUserPassword()
    {
        return $this->userPassword;
    }

    /**
     * Set the value of userPassword
     *
     * @return  self
     */
    public function setUserPassword($userPassword)
    {
        $this->userPassword = $userPassword;

        return $this;
    }

    /**
     * Get the value of userActive
     */
    public function getUserActive()
    {
        return $this->userActive;
    }

    /**
     * Set the value of userActive
     *
     * @return  self
     */
    public function setUserActive($userActive)
    {
        $this->userActive = $userActive;

        return $this;
    }

    /**
     * Get the value of userAvatar
     */
    public function getUserAvatar()
    {
        return $this->userAvatar;
    }

    /**
     * Set the value of userAvatar
     *
     * @return  self
     */
    public function setUserAvatar($userAvatar)
    {
        $this->userAvatar = $userAvatar;

        return $this;
    }

    /**
     * Get the value of userCreatedAt
     */
    public function getUserCreatedAt()
    {
        return $this->userCreatedAt;
    }

    /**
     * Set the value of userCreatedAt
     *
     * @return  self
     */
    public function setUserCreatedAt($userCreatedAt)
    {
        $this->userCreatedAt = $userCreatedAt;

        return $this;
    }

    /**
     * Get the value of token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the value of refreshToken
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * Set the value of refreshToken
     *
     * @return  self
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * Get the value of weight
     */
    public function getUserWeight()
    {
        return $this->userWeight;
    }

    /**
     * Set the value of weight
     *
     * @return  self
     */
    public function setUserWeight($userWeight)
    {
        $this->userWeight = $userWeight;

        return $this;
    }
}
