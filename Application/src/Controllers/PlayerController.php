<?php

namespace Carloscrndn\HelveticBasket\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Carloscrndn\HelveticBasket\Models\Player;
use Carloscrndn\HelveticBasket\Models\Team;
use Carloscrndn\HelveticBasket\Models\User;

class PlayerController extends BaseController
{
    public function showForm(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = User::current();
        if (!$user || !$user->verifyAdmin()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        
        $teams = Team::findAll();
        return $this->view->render($response, 'player/createPlayer.php', [
            'teams' => $teams,
            'data' => [],
            'errors' => [],
        ]);
    }

    public function addPlayers(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        $errors = [];

        $teamId = isset($data['teamId']) ? filter_var($data['teamId'], FILTER_SANITIZE_NUMBER_INT) : null;
        $names = $data['name'] ?? [];

        if (!$teamId || $teamId === false) {
            $errors['team'] = "L'équipe est invalide";
        }

        foreach ($names as $index => $name) {
            $name = trim(filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            if (empty($name)) {
                $errors["name_$index"] = "Le nom du joueur #" . ($index + 1) . " est invalide";
            }
        }

        if (!empty($errors)) {
            return $this->view->render($response, 'player/createPlayer.php', [
                'teams' => Team::findAll(),
                'data' => $data,
                'errors' => $errors,
            ]);
        }

        foreach ($names as $name) {
            $player = new Player();
            $player->setName(trim($name));
            $player->setTeamId((int)$teamId);
            $player->save();
        }

        return $response->withHeader('Location', '/match/create')->withStatus(302);
    }

}