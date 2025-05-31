<?php
declare(strict_types=1);
namespace Carloscrndn\HelveticBasket\Models;
use Carloscrndn\HelveticBasket\Core\Database;
use PDO;

class PlayerStats {
    private int $id;
    private int $idMatch;
    private int $idPlayer;
    private int $points;
    private int $minutes;

    private ?string $playerName = null;
    private ?int $teamId = null; 

     // Getters
     public function getId(): int {
        return $this->id;
    }

    public function getIdPlayer(): int {
        return $this->idPlayer;
    }

    public function getIdMatch(): int {
        return $this->idMatch;
    }

    public function getPoints(): int {
        return $this->points;
    }

    public function getMinutes(): int {
        return $this->minutes;
    }

    public function getPlayerName(): ?string {
        return $this->playerName;
    }

    public function getTeamId(): ?int {
        return $this->teamId;
    }

    private static function hydrate(array $row): self {
        $ps = new self();
        $ps->id = (int)$row['id'];
        $ps->idPlayer = (int)$row['idPlayer'];
        $ps->idMatch = (int)$row['idMatch'];
        $ps->points = (int)$row['points'];
        $ps->minutes = (int)$row['minutes'];

        if (isset($row['name'])) {
            $ps->playerName = $row['name'];
        }

        if (isset($row['idTeam'])) {
            $ps->teamId = (int)$row['idTeam'];
        }

        return $ps;
    }

    public static function findByMatch(int $matchId): array {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            SELECT ps.*, p.name, p.idTeam
            FROM PlayerStats ps
            JOIN Player p ON ps.idPlayer = p.id
            WHERE ps.idMatch = :matchId
            ORDER BY p.name ASC
        ");
        $stmt->execute(['matchId' => $matchId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([self::class, 'hydrate'], $rows);
    }

    public static function updateStatsForMatch(int $statId, int $points, int $minutes): void {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            UPDATE PlayerStats
            SET points = :points, minutes = :minutes
            WHERE id = :statId
        ");
        $stmt->execute([
            'statId' => $statId,
            'points' => $points,
            'minutes' => $minutes
        ]);
    }
    
}