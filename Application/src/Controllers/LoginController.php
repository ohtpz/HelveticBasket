<?php

namespace Carloscrndn\HelveticBasket\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Carloscrndn\HelveticBasket\Models\User;

class LoginController extends BaseController
{
    public function show(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->view->render($response, 'home/login.php', [
            'data' => [],
            'errors' => [],
        ]);
    }

    public function validate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        $errors = [];

        $email = isset($data['email']) ? filter_var($data['email'], FILTER_VALIDATE_EMAIL) : null;
        $password = isset($data['password']) ? filter_var($data['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : null;

        if ($email === false) {
            $errors['email'] = "L'email est invalide";
        }

        if ($data['password'] !== null && trim($data['password']) === '') {
            $errors['password'] = "Le mot de passe est obligatoire";
        }

        if (!empty($errors)) {
            return $this->view->render($response, "home/login.php", [
                'title' => 'Login',
                'withMenu' => false,
                'data' => $data,
                'errors' => $errors,
            ]);
        }

        $user = User::fetchByEmail($email);

        if (!$user || !password_verify($password, $user->getPassword())) {
            return $this->view->render($response, "home/login.php", [
                'title' => 'Login',
                'withMenu' => false,
                'data' => $data,
                'errors' => [
                    'credentials' => 'Les identifiants fournis sont invalides',
                ]
            ]);
        }

        $user->connect();

        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }

    public function logout(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $user = USER::current();
        $user->logout();

        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }
}
