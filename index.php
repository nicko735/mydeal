<?php 

//подключаем файл с конфигом
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/config.php'); 

//
include_template($user_name, $projects, $tasks);

?>