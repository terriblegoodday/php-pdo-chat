<?php

class User {
    private $login;
    private $password;

    public function __construct($login, $password) {
        $this->login = $login;
        $this->password = $password;
    }

    public function getLogin() {
        return $this->login;
    }

    public function getPassword() {
        return $this->password;
    }

    // This is Active Record (just for example)
    public function save($context) {
        $row = $this->mapUserToRow();
        $query = $context->prepare("INSERT INTO users (login, password) VALUES (?, ?)");
        $query->bindParam(1, $row["login"], PDO::PARAM_STR);
        $query->bindParam(1, $row["password"], PDO::PARAM_STR);
        $query->execute();
    }

    public function remove($context) {
        $row = $this->mapUserToRow();
        $query = $context->prepare("DELETE FROM users WHERE login = ?, password = ?");
        $query->bindParam(1, $row["login"], PDO::PARAM_STR);
        $query->bindParam(1, $row["password"], PDO::PARAM_STR);
        $query->execute();
    }

    public static function getByLogin($context, $login): User {
        $query = $context->prepare("SELECT login, password FROM users WHERE login = ?");
        $query->bindParam(1, $login);
        $query->execute();
        $row = $query->fetch(\PDO::FETCH_ASSOC);

        $login = $row["login"];
        $password = $row["password"];

        $user = new User($login, $password);
        return $user;
    }

    public static function retrieveUsers($context): array {
        $query = $context->query("SELECT login, password FROM users");
        $rows = array();
        $query->execute();
        while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
            $login = $row["login"];
            $password = $row["password"];
            $user = new User($login, $password);

            $rows[] = $user;
        }
        return $rows;
    }

    private function mapUserToRow(): array {
        $login = $this->getLogin();
        $password = $this->getPassword();

        $row = [
            "login" => $login,
            "password" => $password
        ];

        return $row;
    }
}

class UserMapper {
    private $storage;

    public function __construct($storage) {
        $this->storage = $storage;
    }

    public function add(User $user) {
        $row = $this->mapUserToRow($user);
        $this->storage->add($row);
    }

    public function remove(User $user) {
        $row = $this->mapUserToRow($user);
        $this->storage->remove($row);
    }

    public function getByLogin(string $login): User {
        $row = $this->storage->getByLogin($login);
        $user = $this->mapRowToUser($row);
        return $user;
    }

    public function retrieveUsers(): array {
        $rows = $this->storage->retrieveUsers();
        $users = array();
        foreach ($rows as $row) {
            $users[] = $this->mapRowToUser($row);
        }

        return $users;
    }

    private function mapRowToUser($row): User {
        $login = $row["login"];
        $password = $row["password"];

        $user = new User($login, $password);

        return $user;
    }

    private function mapUserToRow($user): array {
        $login = $user->getLogin();
        $password = $user->getPassword();

        $row = [
            "login" => $login,
            "password" => $password
        ];

        return $row;
    }
}

?>