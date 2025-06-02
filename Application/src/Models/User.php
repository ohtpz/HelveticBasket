<?php
declare(strict_types=1);

namespace Carloscrndn\HelveticBasket\Models;

use Carloscrndn\HelveticBasket\Core\Database;
use PDO;

class User
{
    private ?int $id = null;
    private ?string $email = null;
    private ?string $passwordHash = null;
    private ?string $name = null;
    private ?bool $isAdmin = null;

    public function getIdUser(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->passwordHash;
    }

    public function verifyAdmin(): bool
    {
        return $this->isAdmin ?? false;
    }

    public static function fetchAll(): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query("SELECT * FROM User");
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

   
    public static function fetchByEmail(string $email): User|false
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT * FROM User WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetch();
    }

    public static function current(): ?User
    {
        static $current = null;

        if (!$current && isset($_SESSION['User'])) {
            $email = $_SESSION['User'];
            $current = self::fetchByEmail($email);
        }

        return $current;
    }

    public static function signup(string $nom, string $email, string $password): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            INSERT INTO User (name, email, passwordHash, isAdmin) 
            VALUES (:name, :email, :passwordHash, :isAdmin)
        ");

        $stmt->execute([
            ':name' => $nom,
            ':email' => $email,
            ':passwordHash' => password_hash($password, PASSWORD_DEFAULT),
            ':isAdmin' => 0
        ]);
    }

    public function connect(): void
    {
        $_SESSION['User'] = $this->email;
        session_regenerate_id(true);
    }

    public function logout(): void 
    {
        unset($_SESSION['User']);
        session_regenerate_id(true);
    }
}
