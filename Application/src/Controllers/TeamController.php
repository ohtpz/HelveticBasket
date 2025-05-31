<?php

namespace Carloscrndn\HelveticBasket\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Carloscrndn\HelveticBasket\Models\Team;
use Carloscrndn\HelveticBasket\Models\Club;
use Carloscrndn\HelveticBasket\Models\Matches;
use Carloscrndn\HelveticBasket\Models\User;
use Carloscrndn\HelveticBasket\Models\UserFavoriteTeams;
use DateTime;

class TeamController extends BaseController
{
    public function createTeam(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {        
        $user = User::current();
        if (!$user || !$user->verifyAdmin()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        
        $clubs = Club::findAll();
        return $this->view->render($response, 'team/createTeam.php', [
            'clubs' => $clubs
        ]);
    }

    public function validate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $data = $request->getParsedBody();
        $errors = [];

        $teamName = isset($data['teamName']) ? trim(filter_var($data['teamName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : null;
        $idClub = $data['idClub'] ?? null;
        $niveau = $data['niveau'] ?? null;
        $region = $data['region'] ?? null;

        if (!$teamName) {
            $errors['teamName'] = "Le nom de l'équipe est obligatoire.";
        }
    
        if (!$idClub || !filter_var($idClub, FILTER_VALIDATE_INT)) {
            $errors['idClub'] = "Le club sélectionné est invalide.";
        }
    
        if (!in_array($niveau, ['U16', 'U18', 'U20'])) {
            $errors['niveau'] = "Le niveau est invalide.";
        }
    
        if (!in_array($region, ['Cantonal', 'Regional', 'National'])) {
            $errors['region'] = "La région est invalide.";
        }

        if (!empty($errors)) {
            $clubs = Club::findAll();
    
            return $this->view->render($response, 'team/create.php', [
                'title' => "Créer une équipe",
                'errors' => $errors,
                'data' => $data,
                'clubs' => $clubs
            ]);
        }

        $team = new Team();
        $team->setTeamName($teamName);
        $team->setIdClub((int)$idClub);
        $team->setLevel($niveau);
        $team->setRegion($region);
        $team->save();
    
        return $response->withHeader('Location', '/match/create')->withStatus(302);
    }

    public function editTeam(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = User::current();
        if (!$user || !$user->verifyAdmin()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $teamId = (int)($args['team'] ?? 0);
        $team = Team::findById($teamId);

        if (!$team) {
            $response->getBody()->write("Équipe introuvable.");
            return $response->withStatus(404);
        }

        $clubs = Club::findAll();

        return $this->view->render($response, 'team/editTeam.php', [
            'team' => $team,
            'clubs' => $clubs,
            'errors' => [],
            'data' => [
                'teamName' => $team->getTeamName(),
                'idClub' => $team->getIdClub(),
                'level' => $team->getLevel(),
                'region' => $team->getRegion()
            ]
        ]);
    }

    public function validateEdit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        $errors = [];

        $teamId = (int)($data['id'] ?? 0);
        $team = Team::findById($teamId);
        if (!$team) {
            $response->getBody()->write("Équipe introuvable.");
            return $response->withStatus(404);
        }

        $teamName = isset($data['teamName']) ? trim(filter_var($data['teamName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : null;
        $idClub = $data['idClub'] ?? null;
        $niveau = $data['niveau'] ?? null;
        $region = $data['region'] ?? null;

        if (!$teamName) {
            $errors['teamName'] = "Le nom de l'équipe est obligatoire.";
        }
    
        if (!$idClub || !filter_var($idClub, FILTER_VALIDATE_INT)) {
            $errors['idClub'] = "Le club sélectionné est invalide.";
        }
    
        if (!in_array($niveau, ['U16', 'U18', 'U20'])) {
            $errors['niveau'] = "Le niveau est invalide.";
        }
    
        if (!in_array($region, ['Cantonal', 'Regional', 'National'])) {
            $errors['region'] = "La région est invalide.";
        }

        if (!empty($errors)) {
            return $this->view->render($response, 'team/editTeam.php', [
                'title' => "Modifier l'équipe",
                'errors' => $errors,
                'data' => $data,
                'clubs' => Club::findAll(),
                'team' => $team
            ]);
        }

        $team->setTeamName($teamName);
        $team->setIdClub((int)$idClub);
        $team->setLevel($niveau);
        $team->setRegion($region);
        $team->update();

        return $response->withHeader('Location', '/settings')->withStatus(302);
    }

    public function showTeam(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $teamId = (int)$args['team'];
        $team = Team::findById($teamId);

        if (!$team) {
            $response->getBody()->write("Équipe introuvable.");
            return $response->withStatus(404);
        }

        $club = Club::findById($team->getIdClub());
        $matches = Matches::findMatchByTeamId($teamId);

        $pastMatches = [];
        $futureMatches = [];
        $currentDate = new DateTime();

        foreach ($matches as $match) {
            $matchDate = new DateTime($match->getDateTime()->format('Y-m-d H:i:s'));
            if ($matchDate < $currentDate) {
                $pastMatches[] = $match;
            } else {
                $futureMatches[] = $match;
            }
        }

        $enrichedPastMatches = $this->enrichMatchesWithTeams($pastMatches, $teamId);
        $enrichedFutureMatches = $this->enrichMatchesWithTeams($futureMatches, $teamId);

        $user = User::current();
        $isFavorite = false;
        
        if ($user && $user->getIdUser() !== null) {
            $isFavorite = UserFavoriteTeams::isFavorite($user->getIdUser(), $teamId);
        }

        return $this->view->render($response, 'team/team.php', [
            'team' => $team,
            'club' => $club,
            'pastMatches' => $enrichedPastMatches,
            'futureMatches' => $enrichedFutureMatches,
            'user' => $user,
            'isFavorite' => $isFavorite
        ]);
    }

    private function enrichMatchesWithTeams(array $matches, int $currentTeamId): array
    {
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
                'isHomeTeam' => $match->getIdHomeTeam() === $currentTeamId
            ];
        }
        return $enrichedMatches;
    }

    public function toggleFavorite(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = User::current();
        if (!$user || $user->getIdUser() === null) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'User not authenticated'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        $teamId = (int)$args['team'];
        $success = UserFavoriteTeams::toggleFavorite($user->getIdUser(), $teamId);
        $isFavorite = UserFavoriteTeams::isFavorite($user->getIdUser(), $teamId);

        $response->getBody()->write(json_encode([
            'success' => $success,
            'isFavorite' => $isFavorite
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function showFavorites(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $user = User::current();
        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        $favoriteTeams = UserFavoriteTeams::getFavoriteTeams($user->getIdUser());

        return $this->view->render($response, 'team/favorites.php', [
            'teams' => $favoriteTeams
        ]);
    }
}
