<?php

class Message {
    private $login;
    private $date;
    private $message;

    public function __construct($login, $date, $message) {
        $this->login = $login;
        $this->date = $date;
        $this->message = $message;
    }

    public function getLogin() {
        return $this->login;
    }

    public function getDate() {
        return $this->date;
    }

    public function getMessage() {
        return $this->message;
    }
}

class MessageMapper {
    private $storage;

    public function __construct($storage) {
        $this->storage = $storage;
    }

    public function add(Message $message) {
        $row = $this->mapMessageToRow($message);
        $this->storage->add($row);
    }

    public function remove(Message $message) {
        $row = $this->mapMessageToRow($message);
        $this->storage->remove($row);
    }

    public function getByLogin(string $login): Message {
        $row = $this->storage->getByLogin($login);
        $message = $this->mapMessageToRow($row);
        return $message;
    }

    public function retrieveMessages(): array {
        $rows = $this->storage->retrieveMessages();
        $messages = array();
        foreach ($rows as $row) {
            $messages[] = $this->mapRowToMessage($row);
        }

        return $messages;
    }

    private function mapRowToMessage($row): Message {
        $login = $row["login"];
        $date = $row["date"];
        $message = $row["message"];

        $message = new Message($login, $date, $message);

        return $message;
    }

    private function mapMessageToRow($message): array {
        $login = $message->getLogin();
        $date = $message->getDate();
        $messageText = $message->getMessage();

        $row = [
            "login" => $login,
            "date" => $date,
            "message" => $messageText
        ];

        return $row;
    }
}

?>