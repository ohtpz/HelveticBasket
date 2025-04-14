<?php
declare(strict_types=1);

namespace Carloscrndn\HelveticBasket\Models;

use Carloscrndn\HelveticBasket\Core\Database;
use PDO;

class User
{
    /**
     * Primary key
     *
     * @var integer|null
     */
    private ?int $idUser = null;

    /**
     * Email field
     *
     * @var string|null
     */
    private ?string $email = null;

    /**
     * Password (hash) field
     *
     * @var string|null
     */
    private ?string $passwordHash = null;

    /**
     * Name field
     *
     * @var string|null
     */
    private ?string $name = null;

    /**
     * Fetch all users
     *
     * @return array
     */
    public static function fetchAll(): array
    {
        $statement = Database::connection()
            ->prepare("select * from User");

        $statement->execute();

        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);

        return $statement->fetchAll();
    }


    
    public static function fetchAllNotCurrentUser(): array {
        $statement = Database::connection()
            ->prepare("select * from User where idUser != :idUser");

        $statement->execute([
            ':idUser' => User::current()->getIdUser()

        ]);
        
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);

        return $statement->fetchAll();
    }
    /**
     * Try to fetch an User by its email
     *
     * @param string $email
     * @return User|false
     */
    public static function fetchByEmail(string $email): User|false
    {
        $statement = Database::connection()
            ->prepare("select * from User where email = :email");

        $statement->execute([
            ':email' => $email
        ]);

        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);

        return $statement->fetch();
    }


    /**
     * Get the User password hash
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->passwordHash;
    }

    /**
     * Get the current User
     *
     * @return User|null
     */
    public static function current(): User|null
    {
        static $current = null;

        if (!$current) {
            $email = $_SESSION['User'] ?? null;

            if ($email !== null) {
                $current = $email ? static::fetchByEmail($email) : new static;
            }
        }

        return $current;
    }

    /**
     * Store User in session
     *
     * @return void
     */
    public function connect(): void
    {
        // Put in session
        $_SESSION['User'] = $this->email;
        session_regenerate_id(true);
    }

    public function getIdUser(): int
    {
        return $this->idUser;
    }

    public function logout() {
        $_SESSION['User'] = null;
    }

    public function isMemberOfShare(int $shareId): bool
    {
        $statement = Database::connection()
            ->prepare("select * from membre where idUser = :idUser and shareId = :shareId");
        $statement->execute([
            'idUser' => $this->getIdUser(),
            'shareId' => $shareId
        ]);

        $member = $statement->fetch();
        
        return $member !== false;
    }
}
