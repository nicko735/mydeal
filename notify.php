<?php 
//подключаем файл с конфигом
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/config.php'); 

//Подключаем свифтмейлер
require_once 'vendor/autoload.php';

//Настройка Smtp на свою яндекс почту
$yandexSmtpHost = 'smtp.yandex.com';
$yandexEmail = 'pawelrm1@yandex.ru';
$yandexPassword = 'Pawel19966';
$yandexSmtpPort = 465;
$yandexEncryption = 'SSL';

$transport = (new Swift_SmtpTransport($yandexSmtpHost, $yandexSmtpPort))
    ->setUsername($yandexEmail)
    ->setPassword($yandexPassword)
    ->setEncryption('SSL');

$mailer = new Swift_Mailer($transport);

$link = mysqli_connect("localhost", "root", "", "mydeal");

if ($link == false){
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
    die;
}
else {
    //Объявление кодировки
    mysqli_set_charset($link, "utf8");

    // Запрос на сегодняшние задачи по почтовым адресам
    $sql = "SELECT COUNT(author_id) tasks_author_cnt, task.author_id, users.email, users.name FROM task
    JOIN users ON users.id = task.author_id
    WHERE task.date_end = CURDATE() and task.task_status = 0 GROUP BY users.email";

    $result = mysqli_query($link, $sql);
    $today_tasks_user = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($today_tasks_user as $value) {
        $targetEmail = $value['email'];
        $author_id = $value['author_id'];
        $user_name = $value['name'];
        $tasks = "";
        $date_end = "";
        $mesage_body = "";

        // Запрос на имена задач отдельной почты
        $sql = "SELECT task.task_name, task.date_end FROM task
        WHERE task.date_end = CURDATE() and task.task_status = 0 and task.author_id = '$author_id'";
        $result = mysqli_query($link, $sql);
        $task_names = mysqli_fetch_all($result, MYSQLI_ASSOC);

        foreach ($task_names as $value) {
            $tasks .= $value['task_name'] . ", ";
        }

        $tasks = substr($tasks, 0, -2);

        $mesage_body= "Уважаемый " . $user_name . ". У вас запланирована задача " . $tasks . " на " . $task_names[0]['date_end'];

        $message = (new Swift_Message('Уведомление от сервиса "Дела в порядке"'))
            ->setFrom([$yandexEmail => 'keks@phpdemo.ru'])
            ->setTo([$targetEmail])
            ->setBody($mesage_body);

        $result = $mailer->send($message);
    }
    mysqli_close ($link);
}
?>