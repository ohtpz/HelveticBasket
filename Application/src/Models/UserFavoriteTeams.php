<?php
declare(strict_types=1);

namespace Carloscrndn\HelveticBasket\Models;

use Carloscrndn\HelveticBasket\Core\Database;
use PDO;
use InvalidArgumentException;

class UserFavoriteTeams {
    private int $idUser;
    private int $idTeam;

    public function __construct(int $idUser, int $idTeam) {
        $this->idUser = $idUser;
        $this->idTeam = $idTeam;
    }

    public static function isFavorite(int $userId, int $teamId): bool {
        if ($userId <= 0 || $teamId <= 0) {
            return false;
        }

        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM UserFavoriteTeams 
            WHERE idUser = :userId AND idTeam = :teamId
        ");
        $stmt->execute(['userId' => $userId, 'teamId' => $teamId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'] > 0;
    }

    public static function toggleFavorite(int $userId, int $teamId): bool {
        if ($userId <= 0 || $teamId <= 0) {
            throw new InvalidArgumentException('Invalid user ID or team ID');
        }

        if (self::isFavorite($userId, $teamId)) {
            return self::removeFavorite($userId, $teamId);
        } else {
            return self::addFavorite($userId, $teamId);
        }
    }

    private static function addFavorite(int $userId, int $teamId): bool {
        if ($userId <= 0 || $teamId <= 0) {
            return false;
        }

        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            INSERT INTO UserFavoriteTeams (idUser, idTeam)
            VALUES (:userId, :teamId)
        ");
        return $stmt->execute(['userId' => $userId, 'teamId' => $teamId]);
    }

    private static function removeFavorite(int $userId, int $teamId): bool {
        if ($userId <= 0 || $teamId <= 0) {
            return false;
        }

        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            DELETE FROM UserFavoriteTeams 
            WHERE idUser = :userId AND idTeam = :teamId
        ");
        return $stmt->execute(['userId' => $userId, 'teamId' => $teamId]);
    }

    public static function getFavoriteTeams(int $userId): array {
        if ($userId <= 0) {
            return [];
        }

        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            SELECT t.*, c.name as clubName, c.logo as clubLogo
            FROM UserFavoriteTeams uft
            JOIN Team t ON uft.idTeam = t.id
            JOIN Club c ON t.idClub = c.id
            WHERE uft.idUser = :userId
        ");
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 