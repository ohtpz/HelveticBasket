<?php
declare(strict_types=1);

namespace Carloscrndn\HelveticBasket\Models;

use Carloscrndn\HelveticBasket\Core\Database;
use PDO;
use DateTime;

class Matches {
    protected static string $table = "Matches";

    private ?int $id = null;
    private ?DateTime $dateTime = null;
    private ?int $homeScore = null;
    private ?int $visitorScore = null;
    private ?int $idHomeTeam = null;
    private ?int $idVisitorTeam = null;

    
    // GETTERS
    public function getId(): ?int {
        return $this->id;
    }

    public function getDateTime(): ?DateTime {
        return $this->dateTime;
    }

    public function getHomeScore(): ?int {
        return $this->homeScore;
    }

    public function getVisitorScore(): ?int {
        return $this->visitorScore;
    }

    public function getIdHomeTeam(): ?int {
        return $this->idHomeTeam;
    }

    public function getIdVisitorTeam(): ?int {
        return $this->idVisitorTeam;
    }

    
    

    // SETTERS
    public function setDateTime(DateTime $dt): void {
        $this->dateTime = $dt;
    }

    public function setHomeScore(int $score): void {
        $this->homeScore = $score;
    }

    public function setVisitorScore(int $score): void {
        $this->visitorScore = $score;
    }

    public function setIdHomeTeam(int $id): void {
        $this->idHomeTeam = $id;
    }

    public function setIdVisitorTeam(int $id): void {
        $this->idVisitorTeam = $id;
    }

  

    // DATABASE FUNCTIONS

    public static function findAll(): array {
        $pdo = Database::connection();
        $stmt = $pdo->query("
            SELECT 
                m.*, 
                th.teamName AS homeTeamName, 
                tv.teamName AS visitorTeamName,
                c.location AS location
            FROM Matches m
            JOIN Team th ON m.idHomeTeam = th.id
            JOIN Team tv ON m.idVisitorTeam = tv.id
            JOIN Club c ON th.idClub = c.id
            ORDER BY m.dateTime ASC
        ");
    
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $matches = [];
    
        foreach ($rows as $row) {
            $match = new Matches();
            $match->setDateTime(new \DateTime($row['dateTime']));
            $match->setHomeScore((int)$row['homeScore']);
            $match->setVisitorScore((int)$row['visitorScore']);
            $match->setIdHomeTeam((int)$row['idHomeTeam']);
            $match->setIdVisitorTeam((int)$row['idVisitorTeam']);

            $match->id = (int)$row['id'];
            $matches[] = $match;
        }
    
        return $matches;
    }

    public static function findById(int $id): ?self {
            $pdo = Database::connection();
        $stmt = $pdo->prepare("
            SELECT m.*, th.teamName AS homeTeamName, tv.teamName AS visitorTeamName, c.location AS location
            FROM Matches m
            JOIN Team th ON m.idHomeTeam = th.id
            JOIN Team tv ON m.idVisitorTeam = tv.id
            JOIN Club c ON th.idClub = c.id
            WHERE m.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $match = new Matches();
        $match->setDateTime(new \DateTime($row['dateTime']));
        $match->setHomeScore((int)$row['homeScore']);
        $match->setVisitorScore((int)$row['visitorScore']);
        $match->setIdHomeTeam((int)$row['idHomeTeam']);
        $match->setIdVisitorTeam((int)$row['idVisitorTeam']);


        $match->id = (int)$row['id'];

        return $match;
    }

    public function save(): bool {
        $pdo = Database::connection();

        if ($this->id === null) {
            $stmt = $pdo->prepare("INSERT INTO " . self::$table . " 
                (date_time, home_score, visitor_score, id_home_team, id_visitor_team)
                VALUES (:dateTime, :homeScore, :visitorScore, :idHomeTeam, :idVisitorTeam)");
            
            return $stmt->execute([
                'dateTime' => $this->dateTime?->format('Y-m-d H:i:s'),
                'homeScore' => $this->homeScore,
                'visitorScore' => $this->visitorScore,
                'idHomeTeam' => $this->idHomeTeam,
                'idVisitorTeam' => $this->idVisitorTeam
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE " . self::$table . " SET
                date_time = :dateTime,
                home_score = :homeScore,
                visitor_score = :visitorScore,
                id_home_team = :idHomeTeam,
                id_visitor_team = :idVisitorTeam
                WHERE id = :id");

            return $stmt->execute([
                'id' => $this->id,
                'dateTime' => $this->dateTime?->format('Y-m-d H:i:s'),
                'homeScore' => $this->homeScore,
                'visitorScore' => $this->visitorScore,
                'idHomeTeam' => $this->idHomeTeam,
                'idVisitorTeam' => $this->idVisitorTeam
            ]);
        }
    }
}
