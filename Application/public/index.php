<?php
// Indiquer les classes à utiliser
use Slim\Views\PhpRenderer;

use Slim\Factory\AppFactory;

// Activer le chargement automatique des classes
require __DIR__ . '/../vendor/autoload.php';

// Initialisation des sessions
session_start();

// Créer l'application
$app = AppFactory::create();

// Ajouter certains traitements d'erreurs
$app->addErrorMiddleware(true, true, true);

// Définir les routes
require __DIR__ . '/../routes/web.php';

// Lancer l'application
$app->run();
