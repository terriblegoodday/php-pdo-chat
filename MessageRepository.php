<?php

interface MessageStorage {
    public function add(array $message);
    public function remove(array $message);
    public function getByLogin(string $id): array;
    public function retrieveMessages(): array;
    public function getByField(string $fieldValue): array;
}

class MessageRepository implements MessageStorage {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function add(array $message) {
        $query = $this->pdo->prepare("INSERT INTO messages (login, date, message) VALUES (?, ?, ?)");
        $query->bindParam(1, $message["login"], PDO::PARAM_STR);
        $query->bindParam(2, $message["date"], PDO::PARAM_STR);
        $query->bindParam(3, $message["message"], PDO::PARAM_STR);
        $query->execute();
    }

    public function remove(array $message) {
        $query = $this->pdo->prepare("DELETE FROM messages WHERE login = ?, date = ?, message = ?");
        $query->bindParam(1, $message["login"]);
        $query->bindParam(2, $message["date"]);
        $query->bindParam(3, $message["message"]);
        $query->execute();
    }

    public function getByLogin(string $login): array {
        $query = $this->pdo->prepare("SELECT login, date, message FROM messages WHERE login = ?");
        $query->bindParam(1, $login);
        $query->execute();
        $row = $query->fetch(\PDO::FETCH_ASSOC);
        return $row;
    }

    public function retrieveMessages(): array {
        $query = $this->pdo->query("SELECT login, date, message FROM messages");
        $rows = array();
        $query->execute();
        while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getByField(string $fieldValue): array {
        $query = $this->pdo->prepare("SELECT ? FROM messages");
        $query->bindParam(1, $fieldValue, PDO::PARAM_STR);
        $query->execute();
        $rows = array();
        while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
            $rows[] = row;
        }

        return $rows;
    }
}

?>