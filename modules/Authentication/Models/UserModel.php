<?php

declare(strict_types=1);

namespace App\Authentication\Models;

use Core\Model;
use Exception;

class UserModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getUsers(): array
    {
        $sql = "SELECT * FROM users";
        return $this->fetchAll($sql);
    }


    public function getUserById($id): array|false
    {
        $sql = "SELECT * FROM users WHERE user_id = $1";
        return $this->fetch($sql, [$id]);
    }


    public function getUserByEmail($email): array|false
    {
        $sql = "SELECT * FROM users WHERE email = $1";
        return $this->fetch($sql, [$email]);
    }


    public function getUserByEmailorUserName($value): array|false
    {
        $sql = "SELECT * FROM users WHERE email = $1 OR name = $1";
        return $this->fetch($sql, [$value]);
    }


    public function createUser($username, $email, $password): string|int|array|false
    {
        $sql = "INSERT INTO users (name, email, password) VALUES ($1, $2, $3) RETURNING user_id";

        $result = $this->query($sql, [
            $username,
            $email,
            password_hash($password, PASSWORD_BCRYPT)
        ]);

        if (!$result) {
            return -1;
        }

        $row = pg_fetch_assoc($result);
        return $row['user_id'];
    }


    public function updateUser($id, $username, $email, $password): int
    {
        $sql = "UPDATE users SET name = $1, email = $2, password = $3 WHERE user_id = $4";
        return $this->execute($sql, [
            $username,
            $email,
            password_hash($password, PASSWORD_BCRYPT),
            $id
        ]);
    }


    public function deleteUser($id): int
    {
        $sql = "DELETE FROM users WHERE user_id = $1";
        return $this->execute($sql, [$id]);
    }
}
