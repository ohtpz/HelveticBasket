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
}
