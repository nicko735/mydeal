<?php 
//подключаем файл с конфигом
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/config.php'); 

$user_id = 1;

//Подключение к MySQL
$link = mysqli_connect("localhost", "root", "", "mydeal");

if ($link == false){
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
    die;
}

//Объявление кодировки
mysqli_set_charset($link, "utf8");

// Получить список из всех проектов для текущего пользователя
// $sql = "SELECT project.id, project.project_name FROM users
// RIGHT JOIN project ON users.id = project.author_id
// WHERE users.id = '$user_id' OR users.id IS NULL";
// $result = mysqli_query($link, $sql);
// $projects_of_users = mysqli_fetch_all($result, MYSQLI_ASSOC);

include($_SERVER['DOCUMENT_ROOT'] . '/templates/add_tmpl.php'); 


?>