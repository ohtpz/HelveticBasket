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
}
