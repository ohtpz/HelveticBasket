<?php

namespace Carloscrndn\HelveticBasket\Models;

use PDO;
use Carloscrndn\HelveticBasket\Core\Database;

class Club
{
    private int $id;
    private string $name;
    private string $logo;
    private string $location;

    public static function findById(int $id): ?self {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT * FROM Club WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return null;

        $club = new self();
        $club->id = (int)$data['id'];
        $club->name = $data['name'];
        $club->logo = $data['logo'];
        $club->location = $data['location'];

        return $club;
    }

    public static function findAll(): array {
        $pdo = Database::connection();
        $stmt = $pdo->query("SELECT * FROM Club");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) return [];

        $clubs = [];
        foreach ($data as $row) {
            $club = new self();
            $club->id = (int)$row['id'];
            $club->name = $row['name'];
            $club->logo = $row['logo'];
            $club->location = $row['location'];

            $clubs[] = $club;
        }

        return $clubs;
    }
    // GETTERS
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getLogo(): string {
        return $this->logo;
    }

    public function getLocation(): string {
        return $this->location;
    }

    // SETTERS
    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setLogo(string $logo): void {
        $this->logo = $logo;
    }

    public function setLocation(string $location): void {
        $this->location = $location;
    }

    // SAVE TO DATABASE
    public function save(): bool {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("INSERT INTO Club (name, logo, location) VALUES (:name, :logo, :location)");
        return $stmt->execute([
            'name' => $this->name,
            'logo' => $this->logo,
            'location' => $this->location
        ]);
    }

    public function update(): bool {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("UPDATE Club SET name = :name, logo = :logo, location = :location WHERE id = :id");
        return $stmt->execute([
            'name' => $this->name,
            'logo' => $this->logo,
            'location' => $this->location,
            'id' => $this->id
        ]);
    }

    public function delete(): bool {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("DELETE FROM Club WHERE id = :id");
        return $stmt->execute(['id' => $this->id]);
    }

}
