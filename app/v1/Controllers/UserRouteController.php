<?php

namespace App\v1\Controllers;

use App\v1\DAO\UserRouteDAO;
use App\v1\Models\UserRouteModel;
use App\v1\Models\UserRoutePathModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserRouteController extends BaseController
{
    /**
     *
     */
    public function getRoutesByUser(Request $request, Response $response, array $args): Response
    {
        $userId = $request->getAttribute('user_id');
        $dataAccessObject = new UserRouteDAO();
        $userRoutes = $dataAccessObject->getRoutesByUser($userId);
        $status = 200;
        header('Content-Type: application/json');
        return $response->withJson($userRoutes, $status);
    }

    /**
     *
     */
    public function getRoute(Request $request, Response $response, array $args): Response
    {
        $userRouteId = $request->getAttribute('user_route_id');

        $dataAccessObject = new UserRouteDAO();
        $userRoute = $dataAccessObject->getRoute($userRouteId);

        if ($userRoute != null) {
            $status = 200;
            header('Content-Type: application/json');
            return $response->withJson($userRoute, $status);
        } else {
            $status = 404;
            $result = array();
            $result["success"] = false;
            $result["message"] = ROUTE_NOT_FOUND;
            header('Content-Type: application/json');
            return $response->withJson($result, $status);
        }
    }

    /**
     *
     */
    public function putUserRouteEndTime(Request $request, Response $response, array $args): Response
    {
        $input = $request->getParsedBody();

        if ($input == null) {
            return $response->withJson(
                array('success' => false, 'message' => ENDPOINT_PARAM_COUNT_ERROR),
                404);
        }

        $requiredData = $this->verifyRequiredParameters(['user_route_id'], $input);
        if ($requiredData['success'] == false) {
            return $response->withJson($requiredData, 404);
        }

        $user_route_id = $input['user_route_id'];

        $dataAccessObject = new UserRouteDAO();
        $dataSaved = $dataAccessObject->putUserRouteEndTime($user_route_id);

        if ($dataSaved) {
            $status = 200;
            $result = array();
            $result["success"] = true;
            $result["message"] = ROUTE_UPDATED;
            header('Content-Type: application/json');
            return $response->withJson($result, $status);
        } else {
            $status = 401;
            $result = array();
            $result["success"] = false;
            $result["message"] = $dataAccessObject->getLastError();
            header('Content-Type: application/json');
            return $response->withJson($result, $status);
        }
    }

    /**
     *
     */
    public function postUserRoute(Request $request, Response $response, array $args): Response
    {
        $input = $request->getParsedBody();

        if ($input == null) {
            return $response->withJson(
                array('success' => false, 'message' => ENDPOINT_PARAM_COUNT_ERROR),
                404);
        }

        $requiredData = $this->verifyRequiredParameters([
            'user_id',
            'user_route_duration',
            'user_route_distance',
            'user_route_calories',
            'user_route_rhythm',
            'user_route_speed',
            'user_route_description',
            'user_route_start_time',
            'user_route_end_time',
            'path',
        ], $input);
        if ($requiredData['success'] == false) {
            return $response->withJson($requiredData, 404);
        }

        $user_id = $input['user_id'];
        $user_route_duration = $input['user_route_duration'];
        $user_route_distance = $input['user_route_distance'];
        $user_route_calories = $input['user_route_calories'];
        $user_route_rhythm = $input['user_route_rhythm'];
        $user_route_speed = $input['user_route_speed'];
        $user_route_description = $input['user_route_description'];
        $user_route_start_time = $input['user_route_start_time'];
        $user_route_end_time = $input['user_route_end_time'];
        $path = $input['path'];

        $userRoute = new UserRouteModel();
        $userRoute->setUserId($user_id)
            ->setUserRouteDuration($user_route_duration)
            ->setUserRouteDistance($user_route_distance)
            ->setUserRouteCalories($user_route_calories)
            ->setUserRouteRhythm($user_route_rhythm)
            ->setUserRouteSpeed($user_route_speed)
            ->setUserRouteDescription($user_route_description)
            ->setUserRouteStartTime($user_route_start_time)
            ->setUserRouteEndTime($user_route_end_time);

        $data = array();
        foreach ($path as $item) {
            $userRoutePath = new UserRoutePathModel();
            $userRoutePath->setUserRoutePathLat($item['user_route_path_latitude'])
                ->setUserRoutePathLng($item['user_route_path_longitude'])
                ->setUserRoutePathAltitude($item['user_route_path_altitude'])
                ->setUserRoutePathDatetime($item['user_route_path_datetime']);

            $data[] = $userRoutePath;
        }

        $dataAccessObject = new UserRouteDAO();
        $userRouteId = $dataAccessObject->postUserRoute($userRoute, $data);

        if (isset($userRouteId)) {
            $status = 200;
            $userRoute->setUserRouteId($userRouteId);

            header('Content-Type: application/json');
            return $response->withJson($userRoute, $status);
        } else {
            $status = 401;
            $result = array();
            $result["success"] = false;
            $result["message"] = $dataAccessObject->getLastError();
            header('Content-Type: application/json');
            return $response->withJson($result, $status);
        }
    }

    /**
     *
     */
    public function deleteUserRoute(Request $request, Response $response, array $args): Response
    {
        $userRouteId = $request->getAttribute('user_route_id');

        $dataAccessObject = new UserRouteDAO();
        $deleted = $dataAccessObject->deleteUserRoute($userRouteId);

        if ($deleted) {
            $status = 200;
            $result = array();
            $result["success"] = true;
            $result["message"] = PDO_DELETE_SUCCESS;
            header('Content-Type: application/json');
            return $response->withJson($result, $status);
        } else {
            $status = 401;
            $result = array();
            $result["success"] = false;
            $result["message"] = $dataAccessObject->getLastError();
            header('Content-Type: application/json');
            return $response->withJson($result, $status);
        }
    }

}
