<?php
//Функция подсчёта задач в проекте
function count_tasks($project_id, $tasks_on_project) {
    $number_of_tasks = 0;

    foreach ($tasks_on_project as $key => $value) {
        if ($value['project_id'] == $project_id) {
            $number_of_tasks = $value['tasks_cnt'];
        }
    }

    return $number_of_tasks;
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

function get_post_val($name) {
    return $_POST[$name] ?? "";
}

function validate_date($name) {
    $test_date = $_POST[$name]; 

    $test_arr = explode('-', $test_date); 
    if (count($test_arr) == 3) {
        if (checkdate($test_arr[1], $test_arr[2], $test_arr[0])) { 
            $date = strtotime($test_date);
            if ($date >= strtotime('today')) {
                return null;
            }
            else {
                return 'Дата должна быть больше или равной сегодняшней';
            }
        } 
        else {
            return 'Введена некорректная дата';
        }
    }
    else {
        if ($test_date == null) {
            return null;
        }
        else {
            return 'Введена некорректная дата';
        }
    }
}

function validate_projects($name, $id_list) {
    $id = $_POST[$name];

    if (!in_array($id, $id_list)) {
        return 'Указан несуществующий проект';
    }

    return null;
}

function validate_email($name, $link) {
    $email = $_POST[$name];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT id FROM users
        WHERE email = '$email'";
        $result = mysqli_query($link, $sql);
        $user_id_of_email = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if (!empty($user_id_of_email)) {
            return 'Данный email уже зарегистрирован';
        }
        else {
            return null;
        }        
    }
    else {
        return 'E-mail введён некорректно';
    }   
}

?>