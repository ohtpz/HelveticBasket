<?php
declare(strict_types=1);

namespace Carloscrndn\HelveticBasket\Models;

use Carloscrndn\HelveticBasket\Core\Database;
use PDO;

class Team {
    private int $id;
    private string $teamName;
    private string $level;
    private int $idClub;
    private string $region;

    public static function findById(int $id): ?self {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT * FROM Team WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return null;

        $team = new self();
        $team->id = (int)$data['id'];
        $team->teamName = $data['teamName'];
        $team->level = $data['level'];
        $team->idClub = (int)$data['idClub'];
        $team->region = $data['region'];

        return $team;
    }

    public static function findAll() {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT * FROM Team");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) return [];

        $teams = [];
        foreach ($data as $row) {
            $team = new self();
            $team->id = (int)$row['id'];
            $team->teamName = $row['teamName'];
            $team->level = $row['level'];
            $team->idClub = (int)$row['idClub'];
            $team->region = $row['region'];

            $teams[] = $team;
        }

        return $teams;
    }

    

    public function save(): bool {
        $pdo = Database::connection();
    
        $stmt = $pdo->prepare("
            INSERT INTO Team (teamName, level, idClub, region)
            VALUES (:teamName, :level, :idClub, :region)
        ");
    
        return $stmt->execute([
            'teamName' => $this->teamName,
            'level' => $this->level,
            'idClub' => $this->idClub,
            'region' => $this->region
        ]);
    }

    public function update(): bool {
        $pdo = Database::connection();
    
        $stmt = $pdo->prepare("
            UPDATE Team
            SET teamName = :teamName, level = :level, idClub = :idClub, region = :region
            WHERE id = :id
        ");
    
        return $stmt->execute([
            'teamName' => $this->teamName,
            'level' => $this->level,
            'idClub' => $this->idClub,
            'region' => $this->region,
            'id' => $this->id
        ]);
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTeamName(): string {
        return $this->teamName;
    }

    public function getLevel(): string {
        return $this->level;
    }

    public function getIdClub(): int {
        return $this->idClub;
    }

    public function getRegion(): string {
        return $this->region;
    }

    public function setTeamName(string $teamName): void {
        $this->teamName = $teamName;
    }
    public function setLevel(string $level): void {
        $this->level = $level;
    }
    public function setIdClub(int $idClub): void {
        $this->idClub = $idClub;
    }
    public function setRegion(string $region): void {
        $this->region = $region;
    }
}
