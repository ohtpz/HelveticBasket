<?php

declare(strict_types=1);

use Carloscrndn\HelveticBasket\Controllers\MatchController;
use Carloscrndn\HelveticBasket\Controllers\LoginController;
use Carloscrndn\HelveticBasket\Controllers\TeamController;
use Carloscrndn\HelveticBasket\Controllers\ClubController;
use Carloscrndn\HelveticBasket\Controllers\PlayerController;

$app->get('/', [MatchController::class, 'home']);
$app->get('/login', [LoginController::class, 'show']);
$app->post('/login', [LoginController::class, 'validate']);
$app->get('/logout', [LoginController::class, 'logout']);
$app->get('/signup', [LoginController::class, 'showSignup']);
$app->post('/signup', [LoginController::class, 'validateSignup']);

$app->get('/settings', [LoginController::class, 'settings']);

// Match
$app->get('/match/{match:[0-9]+}', [MatchController::class, 'match']);
$app->get('/match/edit/{match:[0-9]+}', [MatchController::class, 'showEditMatch']);
$app->post('/match/update/{match:[0-9]+}', [MatchController::class, 'updateMatch']);

// $app->post('/match/delete/{match:[0-9]+}', [MatchController::class, 'deleteMatch']);
$app->get('/match/create', [MatchController::class, 'createMatch']);
$app->post('/match/create', [MatchController::class, 'storeMatch']);

// Team
$app->get('/team/create', [TeamController::class, 'createTeam']);
$app->post('/team/create', [TeamController::class, 'validate']);
$app->get('/team/edit/{team:[0-9]+}', [TeamController::class, 'editTeam']);
$app->post('/team/update', [TeamController::class, 'validateEdit']);
$app->get('/team/{team}', [TeamController::class, 'showTeam']);
$app->post('/team/toggle-favorite/{team:[0-9]+}', [TeamController::class, 'toggleFavorite']);
$app->get('/favoris', [TeamController::class, 'showFavorites']);


// Club
$app->get('/club/create', [ClubController::class, 'createClub']);
$app->post('/club/create', [ClubController::class, 'validate']);
$app->get('/club/edit/{club:[0-9]+}', [ClubController::class, 'editClub']);
$app->post('/club/update', [ClubController::class, 'validateEdit']);
$app->get('/club/delete/{club:[0-9]+}', [ClubController::class, 'deleteClub']);


// Player
$app->get('/player/create', [PlayerController::class, 'showForm']);
$app->post('/player/create', [PlayerController::class, 'addPlayers']);