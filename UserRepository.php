<?php

interface UserStorage {
    public function add(array $user);
    public function remove(string $login);
    public function getByLogin(string $login): array;
    public function retrieveUsers(): array;
    public function getByField(string $fieldValue): array;
}

class UserRepository implements UserStorage {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function add(array $user) {
        $query = $this->pdo>prepare("INSERT INTO users (login, password) VALUES (?, ?)");
        $query->bindParam(1, $user["login"], PDO::PARAM_STR);
        $query->bindParam(2, $user["password"], PDO::PARAM_STR);
        return $query->execute();
    }

    public function remove(string $login) {
        $query = $this->pdo->prepare("DELETE FROM users WHERE login = ?");
        $query->bindParam(1, $login);
        $query->execute;
    }

    public function getByLogin(string $login): array {
        $query = $this->pdo->prepare("SELECT login, password FROM users WHERE login = ?");
        $query->bindParam(1, $login, PDO::PARAM_STR);
        $query->execute();
        $row = $query->fetch(\PDO::FETCH_ASSOC);
        return $row == false ? [] : $row;
    }

    public function retrieveUsers(): array {
        $query = $this->pdo->query("SELECT login, password FROM users");
        $rows = array();
        while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getByField(string $fieldValue): array {
        $query = $this->pdo->prepare("SELECT ? FROM users");
        $query->bindParam(1, $fieldValue, PDO::PARAM_STR);
        $query->execute();
        $rows = array();
        while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }

        return $rows;
    }
}

?>