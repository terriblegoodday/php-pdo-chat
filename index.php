<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Message.php';
require_once __DIR__ . '/MessageRepository.php';
require_once __DIR__ . '/User.php';
require_once __DIR__ . '/UserRepository.php';

const DB_NAME = 'chatroom';
const DB_USER = 'chatroom';
const DB_PASSWORD = 'chatroom';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader, [
    'cache' => __DIR__ . '/cache'
]);

$login = !empty($_GET["login"]) ? $_GET["login"] : '';
$password = !empty($_GET["password"]) ? $_GET["password"] : '';

$pdo = new PDO("mysql:host=localhost;dbname=" . DB_NAME, DB_USER, DB_PASSWORD);

$userRepository = new UserRepository($pdo);
$userMapper = new UserMapper($userRepository);
$user = $userMapper->getByLogin($login);

$isAuthorised = $user->getPassword() == $password ? true : false;

$messageRepository = new MessageRepository($pdo);
$messageMapper = new MessageMapper($messageRepository);

$newMessage = !empty($_GET["message"]) ? $_GET["message"] : '';
if ($isAuthorised && $newMessage != '') {
    $newMessageObject = new Message($login, date("Y/m/d h:i:sa"), $newMessage);
    $messageMapper->add($newMessageObject);
}

$messages = $messageMapper->retrieveMessages();

$templateLogin = $isAuthorised ? $login : '';

echo $twig->render('default.twig', ['messages' => $messages, 'login' => $templateLogin, 'password' => $password]);

?>