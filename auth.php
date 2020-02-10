<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/config.php'); 

//Подключение к MySQL
$link = mysqli_connect("localhost", "root", "", "mydeal");
$username_array = []; // юзернейм от id
$login_error = false; // ошибка авторизации (неверный email/пароль)

if ($link == false){
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
    die;
} else {

    //Объявление кодировки
    mysqli_set_charset($link, "utf8");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $auth = $_POST;
        
        $required = ['email', 'password'];    // обязательные поля
        $errors = [];   // массив ошибок валидации

        // правила валидации
        $rules = [
            'email' => function() {
                return validate_email_auth('email');
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
            $page_content = include_template('auth_tmpl.php', ['auth' => $auth, 'errors' => $errors, 'login_error' => $login_error]);
            $layout = include_template('layout.php', ['page_title' => $username_array, 'page_content' => $page_content, 'script_name' => basename(__FILE__)]);
            print($layout);
        } 
        else {

            $email = $_POST['email'];
            $email = mysqli_real_escape_string($link, $email);

            $password = $_POST['password'];
            $password = mysqli_real_escape_string($link, $password);

            $sql = "SELECT id, pass FROM users WHERE email = '$email'";
            $result = mysqli_query($link, $sql);
            $user_info = mysqli_fetch_all($result, MYSQLI_ASSOC);

            if (empty($user_info)) {
                $login_error = true;

                $page_content = include_template('auth_tmpl.php', ['auth' => $auth, 'errors' => $errors, 'login_error' => $login_error]);
                $layout = include_template('layout.php', ['page_title' => $username_array, 'page_content' => $page_content, 'script_name' => basename(__FILE__)]);
                print($layout);
            }
            else {
                if (password_verify($password, $user_info[0]['pass'])) {
                    session_start();
                    $_SESSION['user_id'] = $user_info[0]['id'];
                    header("Location: /index.php");
                }
                else {
                    $login_error = true;

                    $page_content = include_template('auth_tmpl.php', ['auth' => $auth, 'errors' => $errors, 'login_error' => $login_error]);
                    $layout = include_template('layout.php', ['page_title' => $username_array, 'page_content' => $page_content, 'script_name' => basename(__FILE__)]);
                    print($layout);
                }
            }  

        }
    }
    else {
        $page_content = include_template('auth_tmpl.php', ['login_error' => $login_error]);
        $layout = include_template('layout.php', ['page_title' => $username_array, 'page_content' => $page_content, 'script_name' => basename(__FILE__)]);
        print($layout);
    }

}
?>