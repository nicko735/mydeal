<?php 
//подключаем файл с конфигом
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/config.php'); 

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}
else {
    $user_id = 0;
    header("Location: /index.php");
}

//Подключение к MySQL
$link = mysqli_connect("localhost", "root", "", "mydeal");

if ($link == false){
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
    die;
} else {

    //Объявление кодировки
    mysqli_set_charset($link, "utf8");

    // Получить список из всех проектов для текущего пользователя
    $sql = "SELECT project.id, project.project_name FROM users
    RIGHT JOIN project ON users.id = project.author_id
    WHERE users.id = '$user_id' OR users.id IS NULL";
    $result = mysqli_query($link, $sql);
    $projects_of_users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $projects_id = array_column($projects_of_users, 'id');

    // Получить список из всех задач для текущего пользователя
    $sql = "SELECT * FROM task
    WHERE author_id = '$user_id'";
    $result = mysqli_query($link, $sql);
    $tasks_of_users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    // Запрос для подсчёта задач в проектах у пользователя
    $sql = "SELECT COUNT(id) tasks_cnt, project_id FROM task WHERE author_id = '$user_id' GROUP BY project_id";
    $result = mysqli_query($link, $sql);
    $tasks_on_project = mysqli_fetch_all($result, MYSQLI_ASSOC);

    //Определение имени пользователя
    $sql = "SELECT name FROM users WHERE id = '$user_id'";
    $result = mysqli_query($link, $sql);
    $username_array = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $proj = $_POST;
        
        $required = ['name'];    // обязательные поля
        $errors = [];   // массив ошибок валидации

        // правила валидации
        $rules = [
            'name' => function() use ($projects_of_users) {
                return validate_projects_create('name', $projects_of_users);
            }
        ];

        // обход для проверки соответствия правилам
        foreach ($_POST as $key => $values) {
            if (isset($rules[$key])) {
                $rule = $rules[$key];
                $errors[$key] = $rule();
            }
        }

        $errors = array_filter($errors);

        // обход для проверки полей на пустоту
        foreach ($required as $key) {
            if (empty($_POST[$key])) {
                $errors[$key] = 'Это поле необходимо заполнить';
            }
        }

        // Если есть ошибки то отправим всё в шаблон и отобразим ошибки и набранные в прошлый раз поля
        if (count($errors)) {
            mysqli_close ($link);
            $page_content = include_template('add_proj_tmpl.php', ['projects_of_users' => $projects_of_users, 'tasks_of_users' => $tasks_of_users, 'tasks_on_project' => $tasks_on_project,  'proj' => $proj, 'errors' => $errors]);
            $layout = include_template('layout.php', ['page_title' => $username_array[0]['name'], 'page_content' => $page_content, 'script_name' => basename(__FILE__)]);
            print($layout);
        } 
        else {
            // Если ошибок нет то подготовим все данные для SQL запроса сохранения в БД
            
            $sql_name = $proj['name'];
            $sql_name = mysqli_real_escape_string($link, $sql_name);

            $sql = "INSERT INTO project (author_id, project_name) 
            VALUES ('$user_id', '$sql_name');";

            $result = mysqli_query($link, $sql);

            if ($result == false) {
                print("Произошла ошибка при выполнении запроса");
                print(mysqli_error($link));
                print ($sql);
            } else {
                header("Location: /index.php");
            } 
        }
    }
    else {
        mysqli_close ($link);
        $page_content = include_template('add_proj_tmpl.php', ['projects_of_users' => $projects_of_users, 'tasks_of_users' => $tasks_of_users, 'tasks_on_project' => $tasks_on_project]);
        $layout = include_template('layout.php', ['page_title' => $username_array[0]['name'], 'page_content' => $page_content, 'script_name' => basename(__FILE__)]);
        print($layout);
    }

}
?>