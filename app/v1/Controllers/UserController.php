<?php

namespace App\v1\Controllers;

use App\v1\DAO\UserDAO;
use App\v1\Models\UserModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController extends BaseController
{
    public function getUsers(Request $request, Response $response, array $args): Response
    {
        $dataAccessObject = new UserDAO();
        $users = $dataAccessObject->getUsers();
        $status = 200;
        header('Content-Type: application/json');
        return $response->withJson($users, $status);
    }

    public function getUser(Request $request, Response $response, array $args): Response
    {
        $userId = $request->getAttribute('user_id');

        $dataAccessObject = new UserDAO();
        $user = $dataAccessObject->getUser($userId);

        if ($user != null) {
            $status = 200;
            header('Content-Type: application/json');
            return $response->withJson($user, $status);
        } else {
            $status = 404;
            $result = array();
            $result["success"] = false;
            $result["message"] = USER_NOT_FOUND;
            header('Content-Type: application/json');
            return $response->withJson($result, $status);
        }
    }

    public function postUser(Request $request, Response $response, array $args): Response
    {
        $input = $request->getParsedBody();

        if ($input == null) {
            return $response->withJson(
                array('success' => false, 'message' => ENDPOINT_PARAM_COUNT_ERROR),
                404);
        }

        $requiredData = $this->verifyRequiredParameters(['user_name', 'user_email', 'user_password'], $input);
        if ($requiredData['success'] == false) {
            return $response->withJson($requiredData, 404);
        }

        $user_name = $input['user_name'];
        $user_email = $input['user_email'];
        $user_password = $input['user_password'];

        $hash = getenv('HASH_PASSWORD_KEY');
        $pass_hash = $this->hash('sha512', $user_password, $hash);

        $user = new UserModel();
        $user->setUserName($user_name)
            ->setUserEmail($user_email)
            ->setUserPassword($pass_hash);

        $dataAccessObject = new UserDAO();
        $userId = $dataAccessObject->postUser($user);

        if (isset($userId)) {
            $status = 200;
            $result = array();
            $result['user_id'] = $userId;
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

    public function putUser(Request $request, Response $response, array $args): Response
    {
        $input = $request->getParsedBody();

        if ($input == null) {
            return $response->withJson(
                array('success' => false, 'message' => ENDPOINT_PARAM_COUNT_ERROR),
                404);
        }

        $requiredData = $this->verifyRequiredParameters(['user_id'], $input);
        if ($requiredData['success'] == false) {
            return $response->withJson($requiredData, 404);
        }

        if (count($input) <= 1) {
            $status = 400;
            $result = array();
            $result["success"] = false;
            $result["message"] = ENDPOINT_PARAM_COUNT_ERROR;
            return $response->withJson($result, $status);
        }

        $inputData = new UserModel();
        $inputData->setUserId($input['user_id']);
        $inputData->setUserName($input['user_name']);
        $inputData->setUserEmail($input['user_email']);
        $inputData->setUserPassword($input['user_password']);
        $inputData->setUserActive($input['user_active']);
        $inputData->setUserWeight($input['user_weight']);

        $dataAccessObject = new UserDAO();
        $update = $dataAccessObject->putUser($inputData);

        if ($update) {
            $status = 200;
            $result = array();
            $result["success"] = true;
            $result["message"] = PDO_UPDATE_SUCCESS;
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

    public function deleteUser(Request $request, Response $response, array $args): Response
    {
        $userId = $request->getAttribute('user_id');

        $dataAccessObject = new UserDAO();
        $deleted = $dataAccessObject->deleteUser($userId);

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

    public function changePassword(Request $request, Response $response, array $args): Response
    {
        $input = $request->getParsedBody();

        if ($input == null) {
            return $response->withJson(
                array('success' => false, 'message' => ENDPOINT_PARAM_COUNT_ERROR),
                404);
        }

        $requiredData = $this->verifyRequiredParameters(['user_id', 'user_password', 'user_new_password'], $input);
        if ($requiredData['success'] == false) {
            return $response->withJson($requiredData, 404);
        }

        $userId = $input['user_id'];
        $userPassword = $input['user_password'];
        $userNewPassword = $input['user_new_password'];

        $hash = getenv('HASH_PASSWORD_KEY');
        $passHash = $this->hash('sha512', $userPassword, $hash);
        $newPassHash = $this->hash('sha512', $userNewPassword, $hash);

        $dataAccessObject = new UserDAO();
        $userModel = $dataAccessObject->getUser($userId);

        if ($passHash != $userModel->getUserPassword()) {
            $status = 401;
            $result = array();
            $result["success"] = false;
            $result["message"] = LOGIN_PASSWORD_ERROR;
            header('Content-Type: application/json');
            return $response->withJson($result, $status);
        }

        if ($passHash == $newPassHash) {
            $status = 200;
            $result = array();
            $result["success"] = true;
            $result["message"] = USER_PASSWORD_CHANGED;
            header('Content-Type: application/json');
            return $response->withJson($result, $status);
        }

        $inputData = new UserModel();
        $inputData->setUserId($userId);
        $inputData->setUserName(null);
        $inputData->setUserEmail(null);
        $inputData->setUserSenha($newPassHash);
        $inputData->setUserActive(null);
        $inputData->setUserWeight(null);

        if ($dataAccessObject->putUser($inputData)) {
            $status = 200;
            $result = array();
            $result["success"] = true;
            $result["message"] = USER_PASSWORD_CHANGED;
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

    public function forgotPassword(Request $request, Response $response, array $args): Response
    {
        $input = $request->getParsedBody();

        if ($input == null) {
            return $response->withJson(
                array('success' => false, 'message' => ENDPOINT_PARAM_COUNT_ERROR),
                404);
        }

        $requiredData = $this->verifyRequiredParameters(['user_email'], $input);
        if ($requiredData['success'] == false) {
            return $response->withJson($requiredData, 404);
        }

        $userEmail = $input['user_email'];

        $hash = getenv('HASH_PASSWORD_KEY');

        $pass = substr(md5(rand(999, 999999)), 0, 8);
        $passHash = $this->hash('sha512', $pass, $hash);

        $dataAccessObject = new UserDAO();
        $userModel = $dataAccessObject->getUserByEmail($userEmail);

        if (is_null($userModel)) {
            $status = 401;
            $result = array();
            $result["success"] = false;
            $result["message"] = LOGIN_EMAIL_ERROR;
            header('Content-Type: application/json');
            return $response->withJson($result, $status);
        }

        /*
        Falta implementar o envio do e-mail e remover o campo newPass do retorno (solução provisória)
         */

        $inputData = new UserModel();
        $inputData->setUserId($userModel->getUserId());
        $inputData->setUserName(null);
        $inputData->setUserEmail(null);
        $inputData->setUserPassword($passHash);
        $inputData->setUserActive(null);
        $inputData->setUserWeight(null);

        if ($dataAccessObject->putUser($inputData)) {
            $status = 200;
            $result = array();
            $result["success"] = true;
            $result["message"] = USER_PASSWORD_CHANGED;
            $result["newPass"] = $senha;
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
