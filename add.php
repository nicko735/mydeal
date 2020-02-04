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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $task = $_POST;
        
        $required = ['name', 'project'];    // обязательные поля
        $errors = [];   // массив ошибок валидации

        // правила валидации
        $rules = [
            'project' => function() use ($projects_id) {
                return validate_projects('project', $projects_id);
            },
            'date' => function() {
                return validate_date('date');
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

        // Если загрузили файл то подготовим под него место
        if ($_FILES['file']['name'] !== "") {
            $current_path = $_FILES['file']['tmp_name'];
            $filename = $_FILES['file']['name'];
            $new_path = dirname(__FILE__) . '/' . $filename;
            $task['file'] = $filename;
        }

        // Если есть ошибки то отправим всё в шаблон и отобразим ошибки и набранные в прошлый раз поля
        if (count($errors)) {
            $page_content = include_template('add_tmpl.php', ['projects_of_users' => $projects_of_users, 'tasks_of_users' => $tasks_of_users, 'tasks_on_project' => $tasks_on_project,  'task' => $task, 'errors' => $errors]);
            $layout = include_template('layout.php', ['page_title' => $username_array[0]['name'], 'page_content' => $page_content, 'script_name' => basename(__FILE__)]);
            print($layout);
        } 
        else {
            // Если ошибок нет то переносим файл на постоянку если он есть и подготовим все данные для SQL запроса сохранения в БД
            if ($_FILES['file']['name'] !== "") {
                move_uploaded_file($current_path, $new_path);
                $sql_file ='/' . $task['file'];
                $sql_file = "'" . mysqli_real_escape_string($link, $sql_file) . "'";
            } 
            else {
                $sql_file = 'NULL';
            }
            
            $sql_project = $task['project'];
            $sql_project = mysqli_real_escape_string($link, $sql_project);
            
            $sql_name = $task['name'];
            $sql_name = "'" .  mysqli_real_escape_string($link, $sql_name) . "'";

            if (!empty($task['date'])) {
                $sql_date = $task['date'];
                $sql_date = "'" . mysqli_real_escape_string($link, $sql_date) . "'";
            } 
            else {
                $sql_date = 'NULL';
            }

            $sql = "INSERT INTO task (author_id, date_create, project_id, task_name, date_end, file) 
            VALUES (" . $user_id . ", NOW(), " . $sql_project . ", " . $sql_name . ", " . $sql_date . ", " . $sql_file . ");";

            $result = mysqli_query($link, $sql);

            if ($result == false) {
                print("Произошла ошибка при выполнении запроса");
                print(mysqli_error($link));
                //print ($sql);
            } else {
                header("Location: /index.php");
            } 
        }
    }
    else {
        $page_content = include_template('add_tmpl.php', ['projects_of_users' => $projects_of_users, 'tasks_of_users' => $tasks_of_users, 'tasks_on_project' => $tasks_on_project]);
        $layout = include_template('layout.php', ['page_title' => $username_array[0]['name'], 'page_content' => $page_content, 'script_name' => basename(__FILE__)]);
        print($layout);
    }

}
?>