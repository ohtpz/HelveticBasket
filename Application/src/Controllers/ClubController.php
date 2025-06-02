<?php

namespace Carloscrndn\HelveticBasket\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Carloscrndn\HelveticBasket\Models\User;
use Carloscrndn\HelveticBasket\Models\Club;

class ClubController extends BaseController {

    public function createClub(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->view->render($response, 'club/createClub.php', [
            'data' => [],
            'errors' => [],
        ]);
    }

    public function validate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        $files = $request->getUploadedFiles();

        $errors = [];
        $path = 'img/';

        $clubName = isset($data['clubName']) ? filter_var($data['clubName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : null;
        $location = isset($data['location']) ? filter_var($data['location'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : null;

        if ($clubName === false) {
            $errors['clubName'] = "Le nom du club est invalide";
        }

        if ($location === false) {
            $errors['location'] = "La location est invalide";
        }

        if (!isset($files['logoClub']) || $files['logoClub']->getError() !== UPLOAD_ERR_OK) {
            $errors['file'] = 'Le fichier est obligatoire';
        } else {
            $uploadedFile = $files['logoClub'];
        }

        if (!empty($errors)) {
            return $this->view->render($response, "club/createClub.php", [
                'title' => 'Créer un club',
                'withMenu' => false,
                'data' => $data,
                'errors' => $errors,
            ]);
        }
        // Gestion de l'upload du fichier
        $uploadedFile = $files['logoClub'];
        $fileName = uniqid() . '.' . pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $filePath = $path . $fileName;

        // Vérifie s'il y a eu une erreur d'upload
        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            $errors['file'] = "Erreur d'upload du fichier.";
            return $this->view->render($response, 'recette/form.php', ['errors' => $errors]);
        }

        // Vérifie l'extension du fichier
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExtensions)) {
            $errors['file'] = "Le fichier doit être une image (.jpg, .jpeg, .png)";
            return $this->view->render($response, 'recette/form.php', ['errors' => $errors]);
        }

        // Déplace le fichier dans le dossier
        $uploadedFile->moveTo($filePath);


        
        $club = new Club();
        $club->setName($clubName);
        $club->setLocation($location);
        $club->setLogo($fileName);

        $club->save();

       
        return $response->withHeader('Location', '/team/create')->withStatus(302);

    }

    public function editClub(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $clubId = (int)$args['club'];
        $club = Club::findById($clubId);
       
        if (!$club) {
            $response->getBody()->write("Équipe introuvable.");
            return $response->withStatus(404);
        }
        return $this->view->render($response, 'club/editClub.php', [
            'club' => $club,
            'errors' => [],
        ]);
    }

    public function validateEdit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        $files = $request->getUploadedFiles();
        $errors = [];

        // Récupère l'objet Club
        $clubId = (int)($data['id'] ?? 0);
        $club = Club::findById($clubId);

        if (!$club) {
            $response->getBody()->write("Club non trouvé.");
            return $response->withStatus(404);
        }
        

        // Sanitize inputs
        $clubName = trim(filter_var($data['clubName'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $location = trim(filter_var($data['location'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        // Validate
        if (empty($clubName)) {
            $errors['clubName'] = "Le nom du club est obligatoire.";
        }

        if (empty($location)) {
            $errors['location'] = "La localisation est obligatoire.";
        }

        $uploadedFile = $files['logoClub'] ?? null;
        $logoFilename = $club->getLogo(); // default to existing logo

        if ($uploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExtensions)) {
                $errors['file'] = "Le logo doit être une image valide (.jpg, .jpeg, .png).";
            } else {
                $logoFilename = uniqid() . '.' . $ext;
                $uploadedFile->moveTo('img/' . $logoFilename);
            }
        }

        // Show form again if errors
        if (!empty($errors)) {
            return $this->view->render($response, 'club/editClub.php', [
                'club' => $club,
                'errors' => $errors
            ]);
        }

        // Update club
        $club->setName($clubName);
        $club->setLocation($location);
        $club->setLogo($logoFilename);
        $club->update();

        return $response->withHeader('Location', '/settings')->withStatus(302);
    }

    public function deleteClub(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = User::current();
        if (!$user || !$user->verifyAdmin()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $clubId = (int)$args['club'];
        $club = Club::findById($clubId);
        if (!$club) {
            return $response->withStatus(404)->write('Club not found');
        }

        // Suppression de l'image du logo
        $logoPath = 'img/' . $club->getLogo();
        if (file_exists($logoPath)) {
            unlink($logoPath); // Suppression du fichier
        }

        $club->delete();

        return $response->withHeader('Location', '/settings')->withStatus(302);
    }
}