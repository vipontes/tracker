<?php

namespace App\v1\Controllers;

use App\v1\DAO\UserRoutePathDAO;
use App\v1\Models\UserRoutePathModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserRoutePathController extends BaseController
{
    /**
     *
     */
    public function getPathByRoute(Request $request, Response $response, array $args): Response
    {
        $userRouteId = $request->getAttribute('user_route_id');
        $dataAccessObject = new UserRoutePathDAO();
        $userRoutePaths = $dataAccessObject->getPathByRoute($userRouteId);
        $status = 200;
        header('Content-Type: application/json');
        return $response->withJson($userRoutePaths, $status);
    }

    /**
     *
     */
    public function postUserRoutePath(Request $request, Response $response, array $args): Response
    {
        $input = $request->getParsedBody();

        if ($input == null) {
            return $response->withJson(
                array('success' => false, 'message' => ENDPOINT_PARAM_COUNT_ERROR),
                404);
        }

        $requiredData = $this->verifyRequiredParameters([
            'user_route_id',
            'user_route_path_lat',
            'user_route_path_lng',
        ], $input);
        if ($requiredData['success'] == false) {
            return $response->withJson($requiredData, 404);
        }

        $user_route_id = $input['user_route_id'];
        $user_route_path_lat = $input['user_route_path_lat'];
        $user_route_path_lng = $input['user_route_path_lng'];

        $userRoutePath = new UserRoutePathModel();
        $userRoutePath->setUserRouteId($user_route_id)
            ->setUserRoutePathLat($user_route_path_lat)
            ->setUserRoutePathLng($user_route_path_lng);

        $dataAccessObject = new UserRoutePathDAO();
        $dataSaved = $dataAccessObject->putUserRoutePath($userRoutePath);

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

}
