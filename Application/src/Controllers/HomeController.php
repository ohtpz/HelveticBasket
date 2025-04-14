<?php

namespace Carloscrndn\HelveticBasket\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Carloscrndn\HelveticBasket\Models\Matches;
use Carloscrndn\HelveticBasket\Models\Team;
use Carloscrndn\HelveticBasket\Models\Club;

class HomeController extends BaseController
{
    public function home(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $matches = Matches::findAll();

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
            'matches' => $enrichedMatches
        ]);
    }
    
    public function login(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->view->render($response, 'home/login.php');
    }

    public function match(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $matchId = (int)$args['match'];
        $match = Matches::findById($matchId);
        $homeTeam = Team::findById($match->getIdHomeTeam());
        $visitorTeam = Team::findById($match->getIdVisitorTeam());

        $homeClub = Club::findById($homeTeam->getIdClub());
        $visitorClub = Club::findById($visitorTeam->getIdClub());
        
        if (!$match) {
            $response->getBody()->write("Match not found");
            return $response->withStatus(404);
        }

        return $this->view->render($response, 'match/match.php', [
            'match' => $match,
            'homeTeam' => $homeTeam,
            'visitorTeam' => $visitorTeam,
            'homeClub' => $homeClub,
            'visitorClub' => $visitorClub
        ]);
    }
}