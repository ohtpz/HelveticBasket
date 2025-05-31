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

    public static function findAllFiltered(?string $niveau, ?string $region): array
    {
        $pdo = Database::connection();

        $query = "
            SELECT m.*
            FROM Matches m
            JOIN Team th ON m.idHomeTeam = th.id
            WHERE 1=1
        ";

        $params = [];

        if ($niveau && in_array($niveau, ['U16', 'U18', 'U20'])) {
            $query .= " AND th.level = :niveau";
            $params['niveau'] = $niveau;
        }

        if ($region && in_array($region, ['Cantonal', 'Regional', 'National'])) {
            $query .= " AND th.region = :region";
            $params['region'] = $region;
        }

        $query .= " ORDER BY m.dateTime ASC";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $matches = [];

        foreach ($rows as $row) {
            $match = new self();
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

    public static function findMatchByTeamId(int $teamId): array {
  
        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            SELECT m.*, th.teamName AS homeTeamName, tv.teamName AS visitorTeamName, c.location AS location
            FROM Matches m
            JOIN Team th ON m.idHomeTeam = th.id
            JOIN Team tv ON m.idVisitorTeam = tv.id
            JOIN Club c ON th.idClub = c.id
            WHERE m.idHomeTeam = :teamId1 OR m.idVisitorTeam = :teamId2
            ORDER BY m.dateTime ASC
        ");
        $stmt->execute(['teamId1' => $teamId, 'teamId2' => $teamId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $matches = [];
        foreach ($rows as $row) {
            $match = new Matches();
            $match->setDateTime(new DateTime($row['dateTime']));
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
                (dateTime, homeScore, visitorScore, idHomeTeam, idVisitorTeam)
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
                dateTime = :dateTime,
                homeScore = :homeScore,
                visitorScore = :visitorScore,
                idHomeTeam = :idHomeTeam,
                idVisitorTeam = :idVisitorTeam
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
