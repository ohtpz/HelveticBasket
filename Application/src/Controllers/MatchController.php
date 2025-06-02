<?php

namespace Carloscrndn\HelveticBasket\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Carloscrndn\HelveticBasket\Models\Matches;
use Carloscrndn\HelveticBasket\Models\Team;
use Carloscrndn\HelveticBasket\Models\Club;
use Carloscrndn\HelveticBasket\Models\PlayerStats;
use Carloscrndn\HelveticBasket\Models\Player;
use Carloscrndn\HelveticBasket\Models\User;


use DateTime;
class MatchController extends BaseController
{
    // Affiche tous les matches
    public function home(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $params = $request->getQueryParams();

        $niveau = $params['niveau'] ?? null;
        $region = $params['region'] ?? null;

        $matches = Matches::findAllFiltered($niveau, $region);

        $enrichedMatches = [];

        foreach ($matches as $match) {
            $homeTeam = Team::findById($match->getIdHomeTeam());
            $visitorTeam = Team::findById($match->getIdVisitorTeam());
        
            $homeClub = Club::findById($homeTeam->getIdClub());
            $visitorClub = Club::findById($visitorTeam->getIdClub());
        
            $enrichedMatches[] = [
                'match' => $match,
                'homeTeam' => $homeTeam,
                'visitorTeam' => $visitorTeam,
                'homeClub' => $homeClub,
                'visitorClub' => $visitorClub,
            ];
        }
        return $this->view->render($response, 'home/home.php', [
            'niveau' => $niveau,
            'region' => $region,
            'matches' => $enrichedMatches
        ]);
    }
    
    // Affiche les info du match
    public function match(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $matchId = (int)$args['match'];
        $match = Matches::findById($matchId);

        if (!$match) {
            $response->getBody()->write("Match not found");
            return $response->withStatus(404);
        }

        $homeTeam = Team::findById($match->getIdHomeTeam());
        $visitorTeam = Team::findById($match->getIdVisitorTeam());

        $homeClub = Club::findById($homeTeam->getIdClub());
        $visitorClub = Club::findById($visitorTeam->getIdClub());

        $playerStats = PlayerStats::findByMatch($matchId);

        $homePlayers = [];
        $visitorPlayers = [];

        foreach ($playerStats as $stat) {
            if ($stat->getTeamId() === $homeTeam->getId()) {
                $homePlayers[] = $stat;
            } elseif ($stat->getTeamId() === $visitorTeam->getId()) {
                $visitorPlayers[] = $stat;
            }
        }

        return $this->view->render($response, 'match/match.php', [
            'match' => $match,
            'homeTeam' => $homeTeam,
            'visitorTeam' => $visitorTeam,
            'homeClub' => $homeClub,
            'visitorClub' => $visitorClub,
            'homePlayers' => $homePlayers,
            'visitorPlayers' => $visitorPlayers
        ]);
    }

    public function createMatch(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = User::current();
        if (!$user || !$user->verifyAdmin()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $teams = Team::findAll();

        $players = Player::findAll();

        return $this->view->render($response, 'match/createMatch.php', [
            'teams' => $teams,
            'players' => $players
        ]);
    }

    public function showEditMatch(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = User::current();
        if (!$user || !$user->verifyAdmin()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $matchId = (int)$args['match'];
        $match = Matches::findById($matchId);

        if (!$match) {
            $response->getBody()->write("Match not found");
            return $response->withStatus(404);
        }

        $homeTeam = Team::findById($match->getIdHomeTeam());
        $visitorTeam = Team::findById($match->getIdVisitorTeam());

        $homeClub = $homeTeam ? Club::findById($homeTeam->getIdClub()) : null;
        $visitorClub = $visitorTeam ? Club::findById($visitorTeam->getIdClub()) : null;

        // Get all player stats for this match
        $playerStats = PlayerStats::findByMatch($matchId);

        $homePlayers = [];
        $visitorPlayers = [];

        foreach ($playerStats as $stat) {
            if ($stat->getTeamId() === $homeTeam->getId()) {
                $homePlayers[] = $stat;
            } elseif ($stat->getTeamId() === $visitorTeam->getId()) {
                $visitorPlayers[] = $stat;
            }
        }

        return $this->view->render($response, 'match/editMatch.php', [
            'user' => $user,
            'match' => $match,
            'homeTeam' => $homeTeam,
            'visitorTeam' => $visitorTeam,
            'homeClub' => $homeClub,
            'visitorClub' => $visitorClub,
            'homePlayers' => $homePlayers,
            'visitorPlayers' => $visitorPlayers
        ]);
    }

    public function updateMatch(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = User::current();
        if (!$user || !$user->verifyAdmin()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        
        $matchId = (int)($args['match'] ?? 0);
        $data = $request->getParsedBody();

        $match = Matches::findById($matchId);
        if (!$match) {
            $response->getBody()->write("Match introuvable.");
            return $response->withStatus(404);
        }

        // Vérification de la date et l'heure
        if (empty($data['matchDate']) || empty($data['matchTime'])) {
            $response->getBody()->write("La date et l'heure sont requises.");
            return $response->withStatus(400);
        }

        try {
            $matchDateTime = new DateTime($data['matchDate'] . ' ' . $data['matchTime']);
            $match->setDateTime($matchDateTime);
        } catch (\Exception $e) {
            $response->getBody()->write("Format de date ou d'heure invalide.");
            return $response->withStatus(400);
        }

        // Scores
        $homeScore = isset($data['homeScore']) ? (int)$data['homeScore'] : 0;
        $visitorScore = isset($data['visitorScore']) ? (int)$data['visitorScore'] : 0;

        // Mise à jour du match
        $match->setHomeScore($homeScore);
        $match->setVisitorScore($visitorScore);
        $match->save();

        // Get all player stats for this match to update them
        $playerStats = PlayerStats::findByMatch($matchId);

        // Update home team player stats
        if (isset($data['homePoints']) && isset($data['homeMinutes'])) {
            foreach ($data['homePoints'] as $statId => $points) {
                $minutes = $data['homeMinutes'][$statId] ?? 0;
                PlayerStats::updateStatsForMatch((int)$statId, (int)$points, (int)$minutes);
            }
        }

        // Update visitor team player stats
        if (isset($data['visitorPoints']) && isset($data['visitorMinutes'])) {
            foreach ($data['visitorPoints'] as $statId => $points) {
                $minutes = $data['visitorMinutes'][$statId] ?? 0;
                PlayerStats::updateStatsForMatch((int)$statId, (int)$points, (int)$minutes);
            }
        }

        return $response->withHeader('Location', '/match/' . $matchId)->withStatus(302);
    }

    // Supprimer un match
    public function deleteMatch(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = User::current();
        if (!$user || !$user->verifyAdmin()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $matchId = (int)$args['match'];
        $match = Matches::findById($matchId);

        if (!$match) {
            $response->getBody()->write("Match not found");
            return $response->withStatus(404);
        }

        // Suppression des statistiques des joueurs
        PlayerStats::deleteByMatch($matchId);

        // Suppression du match
        $match->delete();

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    // Enregistre un nouveau match
    public function storeMatch(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = User::current();
        if (!$user || !$user->verifyAdmin()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $data = $request->getParsedBody();

        // Vérification des équipes sélectionnées
        if (empty($data['idHomeTeam']) || empty($data['idVisitorTeam'])) {
            $response->getBody()->write("Équipe recevante et visiteur sont requis.");
            return $response->withStatus(400);
        }

        // Vérification de la date et l'heure
        if (empty($data['matchDate']) || empty($data['matchTime'])) {
            $response->getBody()->write("La date et l'heure sont requises.");
            return $response->withStatus(400);
        }

        $homeTeamId = (int)$data['idHomeTeam'];
        $visitorTeamId = (int)$data['idVisitorTeam'];

        // Création de la date et heure du match
        try {
            $matchDateTime = new DateTime($data['matchDate'] . ' ' . $data['matchTime']);
        } catch (\Exception $e) {
            $response->getBody()->write("Format de date ou d'heure invalide.");
            return $response->withStatus(400);
        }

        // Création du match
        $match = new Matches();
        $match->setIdHomeTeam($homeTeamId);
        $match->setIdVisitorTeam($visitorTeamId);
        $match->setDateTime($matchDateTime);
        $match->setHomeScore(0);
        $match->setVisitorScore(0);
        
        if (!$match->save()) {
            $response->getBody()->write("Erreur lors de la création du match.");
            return $response->withStatus(500);
        }

        $matchId = $match->getId();

        // Récupération des joueurs sélectionnés
        $homePlayers = $data['homePlayers'] ?? [];
        $visitorPlayers = $data['visitorPlayers'] ?? [];

        // Insertion dans PlayerStats
        foreach ($homePlayers as $idPlayer) {
            PlayerStats::create([
                'idPlayer' => (int)$idPlayer,
                'idMatch' => $matchId,
                'points' => 0,
                'minutes' => 0
            ]);
        }

        foreach ($visitorPlayers as $idPlayer) {
            PlayerStats::create([
                'idPlayer' => (int)$idPlayer,
                'idMatch' => $matchId,
                'points' => 0,
                'minutes' => 0
            ]);
        }

        return $response->withHeader('Location', '/match/' . $match-getId())->withStatus(302);
    }

}