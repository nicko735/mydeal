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


//Функция для подключения шаблона
function include_template($page_title, array $projects, array $tasks, array $tasks_on_project) {
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