<?php

namespace App\v1\Controllers;

use App\v1\DAO\UserRouteDAO;
use App\v1\Models\UserRouteModel;
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

        if ($userRoutes != null) {
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

        $requiredData = $this->verifyRequiredParameters(['user_id', 'user_route_description'], $input);
        if ($requiredData['success'] == false) {
            return $response->withJson($requiredData, 404);
        }

        $user_id = $input['user_id'];
        $user_route_description = $input['user_route_description'];

        $userRoute = new UserRouteModel();
        $userRoute->setUserId($user_id)
            ->setUserRouteDescription($user_route_description)
            ->setUserRouteStartTime(null)
            ->setUserRouteEndTime(null);

        $dataAccessObject = new UserRouteDAO();
        $userRouteId = $dataAccessObject->postUserRoute($userRoute);

        if (isset($userRouteId)) {
            $status = 200;
            $result = array();
            $result['user_route_id'] = $userRouteId;
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