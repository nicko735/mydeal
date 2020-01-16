<?php
//Функция подсчёта задач в проекте
function count_tasks(array $array_of_tasks, $project_name) {
    $number_of_tasks = 0;

    foreach ($array_of_tasks as $key => $value) {
        if ($value['project'] === $project_name) {
            $number_of_tasks++;
        }
    }

    return $number_of_tasks;
}


//Функция для подключения шаблона
function include_template($page_title, array $projects, array $tasks) {
    $page_print = ''; //Переменная в которой будет сгенерированная страничка


    ob_start();     // Начинаем писать в буфер
    include_once(PATH_TPL . '/main.php') ;      // В буфере получим разметку контента
    $page_content = ob_get_clean();     // сохраняем результат и чистим буфер

    ob_start();     // Начинаем писать в буфер
    include_once(PATH_TPL . '/layout.php');      // В буфере получим разметку всей страницы, вставив в лейаут контент из прошлого действия
    $page_print = ob_get_clean();     // сохраняем результат и чистим буфер

    print($page_print);     // Отображаем страницу
}
?>