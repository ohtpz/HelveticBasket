<?php

namespace Carloscrndn\HelveticBasket\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Carloscrndn\HelveticBasket\Models\User;
use Carloscrndn\HelveticBasket\Models\Club;
use Carloscrndn\HelveticBasket\Models\Team;
use Carloscrndn\HelveticBasket\Models\Player;

class LoginController extends BaseController
{
    // Afficher le form
    public function show(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->view->render($response, 'login/login.php', [
            'data' => [],
            'errors' => [],
        ]);
    }

    // Verifie si l'utilisateur a bien mis ses données
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
            return $this->view->render($response, "login/login.php", [
                'title' => 'Login',
                'withMenu' => false,
                'data' => $data,
                'errors' => $errors,
            ]);
        }

        $user = User::fetchByEmail($email);

        if (!$user || !password_verify($password, $user->getPassword())) {
            return $this->view->render($response, "login/login.php", [
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

    public function showSignup(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->view->render($response, 'login/signup.php', [
            'data' => [],
            'errors' => [],
        ]);
    }

    public function validateSignup(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        $errors = [];

        // Validation des données
        if (empty($data['name'])) {
            $errors['name'] = 'Le nom est obligatoire';
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'L\'email est invalide';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Le mot de passe est obligatoire';
        } elseif ($data['password'] !== $data['confirmPassword']) {
            $errors['confirmPassword'] = 'Les mots de passe ne correspondent pas';
        }

        if (!empty($errors)) {
            return $this->view->render($response, 'login/signup.php', [
                'data' => $data,
                'errors' => $errors,
            ]);
        }

        User::signup(filter_var($data['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data['email'], FILTER_VALIDATE_EMAIL),
            filter_var($data['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)
        );
        return $response->withHeader('Location', '/login')->withStatus(302);


    }
    // Logout le user actuellement
    public function logout(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $user = USER::current();
        $user->logout();

        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }

    public function settings(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $user = USER::current();
        if (!$user) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $clubs = Club::findAll();
        $teams = Team::findAll();
        $players = Player::findAll();

        return $this->view->render($response, 'home/settings.php', [
            'user' => $user,
            'errors' => [],
            'clubs' => $clubs,
            'teams' => $teams,
            'players' => $players,
        ]);
    }
}
