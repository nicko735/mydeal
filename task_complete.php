<?php 

session_start();

//Если есть ресурс соединения то продолжаем, нет - на главную
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; 
    
    $params = $_GET;
    //если есть гет параметр с id задачи то продолжаем, нет - на главную
    if(isset($params['task_id'])) {
        $task_id = htmlspecialchars($params['task_id']);

        //Подключение к MySQL
        $link = mysqli_connect("localhost", "root", "", "mydeal");

        if ($link == false){
            print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
            die;
        }
        else {
            //зпрос задачи по полученному id задачи и пользователя
            $sql = "SELECT task_status FROM task
            WHERE author_id = '$user_id' and id = $task_id";
            $result = mysqli_query($link, $sql);
            $task_status = mysqli_fetch_all($result, MYSQLI_ASSOC);
            //Если задача не найдена то на главную, найдена - продолжаем
            if(empty($task_status)) {
                header("Location: /index.php");
            } else {
                //Если у задачи статус "0" то меняем его на "1" и наоборот, после чего редирект
                if ($task_status[0]["task_status"] === "0") {
                    $sql = "UPDATE task SET task_status = '1' WHERE author_id = '$user_id' and id = $task_id";
                    $result = mysqli_query($link, $sql);
                } else {
                    $sql = "UPDATE task SET task_status = '0' WHERE author_id = '$user_id' and id = $task_id";
                    $result = mysqli_query($link, $sql);
                }
            }
            // Если переход был осуществлён с како-то страницы, то переход назад на неё
            if(isset($_SERVER['HTTP_REFERER'])) {
                header("Location: " . $_SERVER['HTTP_REFERER']);
            } else {
                header("Location: /index.php");
            }
        }
    } 
    else {
        header("Location: /index.php");
    }
}
else {
    header("Location: /index.php");
}
?>