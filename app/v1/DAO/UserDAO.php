<?php

namespace App\v1\DAO;

use App\v1\Models\UserModel;
use PDOException;

class UserDAO extends Connection
{
    private $lastError = '';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get the value of lastError
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     *
     */
    public function updateTokens(int $userId, string $token, string $refreshToken): bool
    {
        try {
            $sql = "UPDATE user SET
            token = :token,
            refresh_token = :refresh_token
            WHERE user_id = :user_id";

            $sth = $this->pdo->prepare($sql);
            $sth->execute(array(
                ':token' => $token,
                ':refresh_token' => $refreshToken,
                ':user_id' => $userId,
            ));

            return ($sth->rowCount() > 0);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
        }

        return false;
    }

    /**
     *
     */
    public function verifyRefreshToken(string $refreshToken): bool
    {
        $sql = "SELECT user_id FROM user WHERE refresh_token = :refresh_token";

        $sth = $this->pdo->prepare($sql);
        $sth->execute(array(':refresh_token' => $refreshToken));
        $data = $sth->fetch();

        return ($data != false);
    }

    /**
     *
     */
    public function verifyToken(int $userId, string $token): bool
    {
        $sql = "SELECT token FROM user WHERE user_id = :user_id";

        $sth = $this->pdo->prepare($sql);
        $sth->execute(array(':user_id' => $userId));
        $data = $sth->fetch();

        if ($data == false) {
            return false;
        }

        return ($data['token'] == $token);
    }

    /**
     *
     */
    public function getUserByEmail(string $email): ?UserModel
    {
        $sql = "SELECT
            user_id,
            user_name,
            user_email,
            user_password,
            user_active,
            user_avatar,
            user_created_at,
            user_weight,
            token,
            refresh_token
            FROM user
            WHERE user_email = :user_email";

        $sth = $this->pdo->prepare($sql);
        $sth->execute(array(':user_email' => $email));
        $data = $sth->fetch();

        if ($data != false) {
            $user = new UserModel();

            $user->setUserId($data['user_id'])
                ->setUserName($data['user_name'])
                ->setUserEmail($data['user_email'])
                ->setUserPassword($data['user_password'])
                ->setUserActive($data['user_active'])
                ->setUserCreatedAt($data['user_created_at'])
                ->setUserAvatar($data['user_avatar'])
                ->setUserWeight($data['user_weight'])
                ->setToken($data['token'])
                ->setRefreshToken($data['refresh_token']);

            return $user;
        }

        return null;
    }

    /**
     *
     */
    public function getUsers(): ?array
    {
        $sql = "SELECT
            user_id,
            user_name,
            user_email,
            user_password,
            user_active,
            user_avatar,
            user_created_at,
            user_weight,
            token,
            refresh_token
            FROM user
            ORDER BY user_name ASC";

        $sth = $this->pdo->prepare($sql);
        $sth->execute();
        $data = $sth->fetchAll();

        $res = [];

        foreach ($data as $item) {
            $user = new UserModel();

            $user->setUserId($item['user_id'])
                ->setUserName($item['user_name'])
                ->setUserEmail($item['user_email'])
                ->setUserPassword($item['user_password'])
                ->setUserActive($item['user_active'])
                ->setUserCreatedAt($item['user_created_at'])
                ->setUserWeight($item['user_weight'])
                ->setUserAvatar($item['user_avatar'])
                ->setToken($item['token'])
                ->setRefreshToken($item['refresh_token']);

            $res[] = $user;
        }

        return $res;
    }

    /**
     *
     */
    public function getUser(int $userId): ?UserModel
    {
        $sql = "SELECT
            user_id,
            user_name,
            user_email,
            user_password,
            user_active,
            user_avatar,
            user_created_at,
            user_weight,
            token,
            refresh_token
            FROM user
            WHERE user_id = :user_id";

        $sth = $this->pdo->prepare($sql);
        $sth->execute(array(':user_id' => $userId));
        $data = $sth->fetch();

        if ($data != false) {
            $user = new UserModel();

            $user->setUserId($data['user_id'])
                ->setUserName($data['user_name'])
                ->setUserEmail($data['user_email'])
                ->setUserPassword($data['user_password'])
                ->setUserActive($data['user_active'])
                ->setUserCreatedAt($data['user_created_at'])
                ->setUserWeight($data['user_weight'])
                ->setUserAvatar($data['user_avatar'])
                ->setToken($data['token'])
                ->setRefreshToken($data['refresh_token']);

            return $user;
        }

        return null;
    }

    public function postUser(UserModel $user): ?int
    {
        $query = "INSERT INTO user (
            user_name,
            user_email,
            user_password,
            user_active
        ) VALUES (
            :user_name,
            :user_email,
            :user_password,
            1
        )";

        try {
            $sth = $this->pdo->prepare($query);
            $sth->execute([
                ':user_name' => $user->getUserName(),
                ':user_email' => $user->getUserEmail(),
                ':user_password' => $user->getUserPassword(),
            ]);

            $result = $sth->rowCount();

            if ($result > 0) {
                return $this->pdo->lastInsertId();
            } else {
                $this->lastError = PDO_INSERT_ERROR;
            }
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
        }

        return null;
    }

    public function putUser(UserModel $inputData): bool
    {

        $fields = array();
        $values = array();

        if ($inputData->getUserName() != null) {
            array_push($fields, "user_name = :user_name");
            $values[':user_name'] = $inputData->getUserName();
        }

        if ($inputData->getUserEmail() != null) {
            array_push($fields, "user_email = :user_email");
            $values[':user_email'] = $inputData->getUserEmail();
        }

        if ($inputData->getUserPassword() != null) {
            array_push($fields, "user_password = :user_password");
            $values[':user_password'] = $inputData->getUserPassword();
        }

        if ($inputData->getUserActive() != null) {
            array_push($fields, "user_active = :user_active");
            $values[':user_active'] = $inputData->getUserActive();
        }

        if ($inputData->getUserWeight() != null) {
            array_push($fields, "user_weight = :user_weight");
            $values[':user_weight'] = $inputData->getUserWeight();
        }

        $values[':user_id'] = $inputData->getUserId();

        $str_fields = implode(",", $fields);
        $query = "UPDATE user SET " . $str_fields . " WHERE user_id = :user_id";

        try {
            $sth = $this->pdo->prepare($query);
            $sth->execute($values);

            //$result = $sth->rowCount();

            $error_array = $sth->errorInfo();
            $error = (int) $error_array[1];

            if ($error == 0) {
                return true;
            } else {
                $this->lastError = PDO_UPDATE_ERROR;
            }
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
        }

        return false;
    }

    public function deleteUser(int $userId): bool
    {

        $query = "DELETE FROM user WHERE user_id = :user_id";

        try {
            $sth = $this->pdo->prepare($query);
            $result = $sth->execute([':user_id' => $userId]);

            if ($result > 0) {
                return true;
            } else {
                $this->lastError = PDO_DELETE_ERROR;
            }
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
        }

        return false;
    }

}
