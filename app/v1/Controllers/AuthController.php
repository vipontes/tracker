<?php
namespace App\v1\Controllers;

use App\v1\DAO\UserDAO;
use App\v1\Models\UserModel;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends BaseController
{
    private function updateUserToken(UserModel $user): ?array
    {
        // Dados do usuário
        $tokenPayload = [
            'userId' => $user->getUserId(),
            'userEmail' => $user->getUserEmail(),
            'expiredAt' => (new \DateTime())->modify('+2 hour')->format('Y-m-d H:i:s'),
        ];
        $token = JWT::encode($tokenPayload, getenv('JWT_SECRET_KEY'));

        //
        $refreshTokenPayload = [
            'userEmail' => $user->getUserEmail(),
            'random' => uniqid(),
        ];
        $refreshToken = JWT::encode($refreshTokenPayload, getenv('JWT_SECRET_KEY'));

        $dataAccessObject = new UserDAO();
        $updateOk = $dataAccessObject->updateTokens($user->getUserId(), $token, $refreshToken);

        if ($updateOk) {
            return ["token" => $token, "refresh_token" => $refreshToken];
        }

        return null;
    }

    public function login(Request $request, Response $response, array $args): Response
    {
        $input = $request->getParsedBody();

        if ($input == null) {
            return $response->withJson(
                array('success' => false, 'message' => ENDPOINT_PARAM_COUNT_ERROR),
                404);
        }

        $requiredData = $this->verifyRequiredParameters(['user_email', 'user_password'], $input);
        if ($requiredData['success'] == false) {
            return $response->withJson($requiredData, 404);
        }

        $email = $input['user_email'];
        $password = $input['user_password'];
        $userDAO = new UserDAO();
        $user = $userDAO->getUserByEmail($email);

        if (is_null($user)) {
            $status = 401;
            $result = array();
            $result["success"] = false;
            $result["message"] = LOGIN_EMAIL_ERROR;
            return $response->withJson($result, $status);
        }

        if ($user->getUserActive() == 0) {
            $status = 401;
            $result = array();
            $result["success"] = false;
            $result["message"] = LOGIN_INACTIVE;
            return $response->withJson($result, $status);
        }

        $hash = getenv('HASH_PASSWORD_KEY');
        $senha_hash = $this->hash('sha512', $password, $hash);

        if ($senha_hash != $user->getUserPassword()) {
            $status = 401;
            $result = array();
            $result["success"] = false;
            $result["message"] = LOGIN_PASSWORD_ERROR;
            return $response->withJson($result, $status);
        }

        $tokens = $this->updateUserToken($user);

        if ($tokens != null) {
            $response = $response->withJson($tokens, 200);
            return $response;
        } else {
            $status = 401;
            $result = array();
            $result["success"] = false;
            $result["message"] = UPDATE_TOKEN_ERROR;
            return $response->withJson($result, $status);
        }
    }

    public function refreshToken(Request $request, Response $response, array $args): Response
    {
        $input = $request->getParsedBody();

        if ($input == null) {
            return $response->withJson(
                array('success' => false, 'message' => ENDPOINT_PARAM_COUNT_ERROR),
                404);
        }

        $requiredData = $this->verifyRequiredParameters(['refresh_token'], $input);
        if ($requiredData['success'] == false) {
            return $response->withJson($requiredData, 404);
        }

        $refreshToken = $input['refresh_token'];

        $refreshTokenDecoded = JWT::decode(
            $refreshToken,
            getenv('JWT_SECRET_KEY'),
            ['HS256']
        );

        $userDAO = new UserDAO();

        $refreshTokenExists = $userDAO->verifyRefreshToken($refreshToken);
        if (!$refreshTokenExists) {
            $status = 401;
            $result = array();
            $result["success"] = false;
            $result["message"] = INVALID_REFRESH_TOKEN;
            return $response->withJson($result, $status);
        }

        $user = $userDAO->getUserByEmail($refreshTokenDecoded->userEmail);
        $tokens = $this->updateUserToken($user);

        if ($tokens != null) {
            $response = $response->withJson($tokens, 200);
            return $response;
        } else {
            $result = array();
            $result["success"] = false;
            $result["message"] = UPDATE_TOKEN_ERROR;
            return $response->withJson($result, 401);
        }
    }
}
