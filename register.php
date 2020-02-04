<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/config.php'); 

//Подключение к MySQL
$link = mysqli_connect("localhost", "root", "", "mydeal");
$username_array = [];

if ($link == false){
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
    die;
} else {

    //Объявление кодировки
    mysqli_set_charset($link, "utf8");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $reg = $_POST;
        
        $required = ['email', 'password', 'name'];    // обязательные поля
        $errors = [];   // массив ошибок валидации

        // правила валидации
        $rules = [
            'email' => function() use ($link) {
                return validate_email_reg('email', $link);
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
            $page_content = include_template('reg_tmpl.php', ['reg' => $reg, 'errors' => $errors]);
            $layout = include_template('layout.php', ['page_title' => $username_array, 'page_content' => $page_content, 'script_name' => basename(__FILE__)]);
            print($layout);
        } 
        else {
            // Если ошибок нет то сохраняем в БД
            
            $sql_email = $reg['email'];
            $sql_email = mysqli_real_escape_string($link, $sql_email);
            
            $sql_password = $reg['password'];
            $sql_password = mysqli_real_escape_string($link, $sql_password);
            $sql_password = password_hash($sql_password, PASSWORD_DEFAULT);
            

            $sql_name = $reg['name'];
            $sql_name = mysqli_real_escape_string($link, $sql_name);

            $sql = "INSERT INTO users (date_reg, email, pass, name) 
            VALUES ( NOW(), '$sql_email', '$sql_password', '$sql_name');";

            $result = mysqli_query($link, $sql);

            if ($result == false) {
                print("Произошла ошибка при выполнении запроса");
                print(mysqli_error($link));
            } else {
                header("Location: /index.php");
            } 

        }
    }
    else {
        $page_content = include_template('reg_tmpl.php', []);
        $layout = include_template('layout.php', ['page_title' => $username_array, 'page_content' => $page_content, 'script_name' => basename(__FILE__)]);
        print($layout);
    }

}
?>