<?php

namespace App\v1\DAO;

use App\v1\Models\UserRouteModel;
use PDOException;

class UserRouteDAO extends Connection
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
    public function getRoutesByUser(string $userId): array
    {
        $sql = "SELECT
            user_route_id,
            user_id,
            user_route_description,
            user_route_start_time,
            user_route_end_time
            FROM user_route
            WHERE user_id = :user_id
            ORDER BY user_route_id DESC";

        $sth = $this->pdo->prepare($sql);
        $sth->execute(array(':user_id' => $userId));
        $data = $sth->fetchAll();

        $res = [];

        foreach ($data as $item) {
            $userRoute = new UserRouteModel();

            $userRoute->setUserId($item['user_id'])
                ->setUserRouteId($item['user_route_id'])
                ->getUserRouteDescription($item['user_route_description'])
                ->setUserRouteStartTime($item['user_route_start_time'])
                ->setUserRouteEndTime($item['user_route_end_time']);

            $res[] = $userRoute;
        }

        return $res;
    }

    /**
     *
     */
    public function getRoute(string $userRouteId): ?array
    {
        $sql = "SELECT
            user_route_id,
            user_id,
            user_route_description,
            user_route_start_time,
            user_route_end_time
            FROM user_route
            WHERE user_route_id = :user_route_id
            ORDER BY user_route_id DESC";

        $sth = $this->pdo->prepare($sql);
        $sth->execute(array(':user_route_id' => $userRouteId));
        $data = $sth->fetch();

        if ($data != false) {
            $userRoute = new UserRouteModel();

            $userRoute->setUserId($data['user_id'])
                ->setUserRouteId($data['user_route_id'])
                ->getUserRouteDescription($data['user_route_description'])
                ->setUserRouteStartTime($data['user_route_start_time'])
                ->setUserRouteEndTime($data['user_route_end_time']);

            return $userRoute;
        }

        return null;
    }

    /**
     *
     */
    public function postUserRoute(UserRouteModel $userRoute): ?int
    {
        $query = "INSERT INTO user_route (user_id, user_route_description) VALUES (:user_id, :user_route_description)";

        try {
            $sth = $this->pdo->prepare($query);
            $sth->execute([
                ':user_id' => $userRoute->getUserId(),
                ':user_route_description' => $userRoute->getUserRouteDescription(),
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

    /**
     *
     */
    public function putUserRouteEndTime(int $userRouteId): bool
    {
        $userRouteEndTime = date("Y-m-d H:i:s");

        $query = "UPDATE user_route SET user_route_end_time = :user_route_end_time WHERE user_route_id = :user_route_id";

        try {
            $sth = $this->pdo->prepare($query);
            $sth->execute([
                ':user_route_id' => $userRouteId,
                ':user_route_end_time' => $userRouteEndTime,
            ]);

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

    /**
     *
     */
    public function deleteUserRoute(int $userRouteId): bool
    {

        $query = "DELETE FROM user_route WHERE user_route_id = :user_route_id";

        try {
            $sth = $this->pdo->prepare($query);
            $result = $sth->execute([':user_route_id' => $userRouteId]);

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
