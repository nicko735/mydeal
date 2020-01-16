<?php 

// Это будет тайтлом и отображаемым именем пользователя
$user_name = 'Константин';

//Массив с предопределёнными проектами
$projects = [
    "Входящие",
    "Учеба",
    "Работа",
    "Домашние дела",
    "Авто"
];

//Массив с задачами
$tasks = [
    [
        'title' => 'Собеседование в IT компании',
        'date' => '01.12.2019',
        'project' => $projects[2],
        'completed' => false
    ],
    [
        'title' => 'Выполнить тестовое задание',
        'date' => '25.12.2019',
        'project' => $projects[2],
        'completed' => false
    ],
    [
        'title' => 'Сделать задание первого раздела',
        'date' => '21.12.2019',
        'project' => $projects[1],
        'completed' => true
    ],
    [
        'title' => 'Встреча с другом',
        'date' => '22.12.2019',
        'project' => $projects[0],
        'completed' => false
    ],
    [
        'title' => 'Купить корм для кота',
        'date' => null,
        'project' => $projects[3],
        'completed' => false
    ],
    [
        'title' => 'Заказать пиццу',
        'date' => null,
        'project' => $projects[3],
        'completed' => false
    ],

];

?>