<?php
namespace Tracker\Routes;

require 'env.php';
require 'constants.php';

use App\v1\DAO\UserDAO;
use Firebase\JWT\JWT;
use \App\v1\Controllers\AuthController;
use \App\v1\Controllers\UserController;
use \App\v1\Controllers\UserRouteController;
use \App\v1\Controllers\UserRoutePathController;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Tuupola\Middleware\JwtAuthentication;

function slimConfiguration(): \Slim\Container
{
    $configuration = [
        'settings' => [
            'displayErrorDetails' => getenv('DISPLAY_ERRORS_DETAILS'),
        ],
    ];
    return new \Slim\Container($configuration);
}

function jwtAuth(): JwtAuthentication
{
    return new JwtAuthentication([
        'secure' => false,
        'secret' => getenv('JWT_SECRET_KEY'),
        'attribute' => 'jwt',
    ]);
}

final class JwtAuthMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        $tokenPayload = $request->getAttribute('jwt');
        $expireDate = new \DateTime($tokenPayload['expiredAt']);
        $userId = $tokenPayload['userId'];
        $now = new \DateTime();

        // 1 - Verifica a expiração do token
        if ($expireDate < $now) {
            $status = 401;
            $result = array();
            $result["success"] = false;
            $result["expired"] = true;
            $result["message"] = MIDDLEWARE_EXPIRE_ERROR;
            return $response->withJson($result, $status);
        }

        // 2 -  Verifica se o token é válido
        $userDAO = new UserDAO();

        $token = JWT::encode($tokenPayload, getenv('JWT_SECRET_KEY'));

        $tokenValido = $userDAO->verifyToken($userId, $token);
        if ($tokenValido == false) {
            $status = 401;
            $result = array();
            $result["success"] = false;
            $result["message"] = MIDDLEWARE_TOKEN_NOT_VALID;
            return $response->withJson($result, $status);
        }

        $response = $next($request, $response);
        return $response;
    }
}

class App
{
    /**
     * Stores an instance of the Slim application.
     *
     * @var \Slim\App
     */
    private $app;

    public function __construct()
    {
        $app = new \Slim\App(slimConfiguration());

        $app->get('/', function (Request $request, Response $response) {
            $response->getBody()->write("Hello, Todo");
            return $response;
        });

        $app->group('/v1', function () use ($app) {

            $app->post('/login', AuthController::class . ':login');
            $app->post('/refresh-token', AuthController::class . ':refreshToken');
            $app->post('/forgot-password', UserController::class . ':forgotPassword');
            $app->post('/user', UserController::class . ':postUser');

            $app->group('', function () use ($app) {
                // Users
                $app->get('/users', UserController::class . ':getUsers');
                $app->get('/user/{user_id}', UserController::class . ':getUser');
                $app->put('/user', UserController::class . ':putUser');
                $app->put('/user/change-password', UserController::class . ':changePassword');
                $app->delete('/user/{user_id}', UserController::class . ':deleteUser');

                // Routes
                $app->get('/routes/{user_id}', UserRouteController::class . ':getRoutesByUser');
                $app->get('/route/{user_route_id}', UserRouteController::class . ':getRoute');
                $app->post('/route', UserRouteController::class . ':postUserRoute');
                $app->put('/route/end', UserRouteController::class . ':putUserRouteEndTime');
                $app->delete('/route/{user_route_id}', UserRouteController::class . ':deleteUserRoute');

                // RoutePath
                $app->get('/routepath/{user_route_id}', UserRoutePathController::class . ':getPathByRoute');
                $app->post('/routepath', UserRoutePathController::class . ':postUserRoutePath');
            })->add(new JwtAuthMiddleware())->add(jwtAuth());
        });

        $this->app = $app;
    }

    /**
     * Get an instance of the application.
     *
     * @return \Slim\App
     */
    public function get()
    {
        return $this->app;
    }
}
