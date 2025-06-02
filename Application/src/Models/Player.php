<?php
declare(strict_types=1);

namespace Carloscrndn\HelveticBasket\Models;

use Carloscrndn\HelveticBasket\Core\Database;
use PDO;

class Player {

    protected static string $table = "Player";

    private ?int $id = null;
    private ?string $name = null;
    private ?int $idTeam = null;

    // GETTERS

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function getTeamId(): ?int {
        return $this->idTeam;
    }

    // SETTERS

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setTeamId(int $teamId): void {
        $this->idTeam = $teamId;
    }

    // DATABASE FUNCTIONS

    // Cherche tout les joueurs
    public static function findAll(): array {
        $pdo = Database::connection();
        $stmt = $pdo->query("
            SELECT * FROM Player
            ORDER BY name ASC
        ");

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $players = [];

        foreach ($rows as $row) {
            $player = new Player();
            $player->setName($row['name']);
            $player->setTeamId((int)$row['idTeam']);
            $player->id = (int)$row['id'];

            $players[] = $player;
        }

        return $players;
    }

    // Cherche un joueur par son id
    public static function findPlayerById(int $id): ?self {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT * FROM Player WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $player = new Player();
        $player->setName($row['name']);
        $player->setTeamId((int)$row['idTeam']);
        $player->id = (int)$row['id'];

        return $player;
    }

    //Cherche tout les joueurs d'une équipe
    public static function findPlayersByTeamId(int $teamId): array {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT * FROM Player WHERE idTeam = :idTeam");
        $stmt->execute(['idTeam' => $teamId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $players = [];

        foreach ($rows as $row) {
            $player = new Player();
            $player->setName($row['name']);
            $player->setTeamId((int)$row['idTeam']);
            $player->id = (int)$row['id'];

            $players[] = $player;
        }

        return $players;
    }

    public function save(): void {
        $pdo = Database::connection();

        
        $stmt = $pdo->prepare("INSERT INTO Player (name, idTeam) VALUES (:name, :idTeam)");
        $stmt->execute([
            'name' => $this->name,
            'idTeam' => $this->idTeam,
        ]);
        $this->id = (int)$pdo->lastInsertId();
        
    }
}
