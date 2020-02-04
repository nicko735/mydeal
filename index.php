<?php 
//подключаем файл с конфигом
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/config.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}
else {
    $user_id = 0;
}


//Подключение к MySQL
$link = mysqli_connect("localhost", "root", "", "mydeal");

if ($link == false){
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
    die;
}
else {
    //Объявление кодировки
    mysqli_set_charset($link, "utf8");

    // Получить список из всех проектов для текущего пользователя
    $sql = "SELECT project.id, project.project_name FROM users
    RIGHT JOIN project ON users.id = project.author_id
    WHERE users.id = '$user_id' OR users.id IS NULL";
    $result = mysqli_query($link, $sql);
    $projects_of_users = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $params = $_GET;
    // Если есть запрос с project_id то формируется список задач только для заданного проекта текущего пользователя
    if(isset($params['project_id'])) {
        $project_id = htmlspecialchars($params['project_id']);

        $sql = "SELECT * FROM task
        WHERE author_id = '$user_id' and project_id = $project_id";
        $result = mysqli_query($link, $sql);
        $tasks_of_users = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if(empty($tasks_of_users)) {
            header("HTTP/1.1 404 Not Found");
            die;
        }
    } 
    else {
    // Получить список из всех задач для текущего пользователя
    $sql = "SELECT * FROM task
    WHERE author_id = '$user_id'";
    $result = mysqli_query($link, $sql);
    $tasks_of_users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }



    // Запрос для подсчёта задач в проектах у пользователя
    $sql = "SELECT COUNT(id) tasks_cnt, project_id FROM task WHERE author_id = '$user_id' GROUP BY project_id";
    $result = mysqli_query($link, $sql);
    $tasks_on_project = mysqli_fetch_all($result, MYSQLI_ASSOC);

    //Определение имени пользователя
    $sql = "SELECT name FROM users WHERE id = '$user_id'";
    $result = mysqli_query($link, $sql);
    $username_array = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if(!empty($username_array)) {
        $username_array = $username_array[0]['name'];
    }
    else {
        $username_array = [];
    }

    //Сборка и вызов страницы
    if(!empty($username_array)) {
        $page_content = include_template('main.php', ['projects_of_users' => $projects_of_users, 'tasks_of_users' => $tasks_of_users, 'tasks_on_project' => $tasks_on_project]);
    }
    else {
        $page_content = include_template('guest.php', []);
    }
    
    $layout = include_template('layout.php', ['page_title' => $username_array, 'page_content' => $page_content]);
    print($layout);

}
?>