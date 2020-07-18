<?php

namespace App\v1\DAO;

use App\v1\Models\UserRoutePathModel;

class UserRoutePathDAO extends Connection
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
    public function getPathByRoute(string $userRouteId): array
    {
        $sql = "SELECT
            user_route_path_id,
            user_route_id,
            X(user_route_path_latlng) AS lat,
            Y(user_route_path_latlng) AS lng,
            user_route_path_datetime
            FROM user_route_path
            WHERE user_route_id = :user_route_id
            ORDER BY user_route_path_id ASC";

        $sth = $this->pdo->prepare($sql);
        $sth->execute(array(':user_route_id' => $userRouteId));
        $data = $sth->fetchAll();

        $res = [];

        foreach ($data as $item) {
            $userRoutePath = new UserRoutePathModel();

            $userRoutePath->setUserRoutePathId($item['user_route_path_id'])
                ->setUserRouteId($item['user_route_id'])
                ->setUserRoutePathLat($item['lat'])
                ->setUserRoutePathLng($item['lng'])
                ->setUserRoutePathDatetime($item['user_route_path_datetime']);

            $res[] = $userRoute;
        }

        return $res;
    }

    /**
     *
     */
    public function postUserRoutePath(UserRoutePathModel $userRoutePath): ?int
    {
        $query = "INSERT INTO user_route_path (user_route_id, user_route_path_latlng)
        VALUES (:user_route_id, POINT(:user_route_path_lat, :user_route_path_lng)";

        try {
            $sth = $this->pdo->prepare($query);
            $sth->execute([
                ':user_route_id' => $userRoutePath->getUserRouteId(),
                ':user_route_path_lat' => $userRoutePath->getUserRoutePathLat(),
                ':user_route_path_lng' => $userRoutePath->getUserRoutePathLng(),
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
}
