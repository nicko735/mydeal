<?php // показывать или нет выполненные задачи
//$show_complete_tasks = rand(0, 1);
$show_complete_tasks = 1;

$params = $_GET;
if(isset($params['date_filter'])) {
    $date_filter = htmlspecialchars($params['date_filter']);
} else {
    $date_filter = "";
}
?>

<?php require_once 'block/sidebar_menu.php'; ?>

<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="get" autocomplete="off">
        <input class="search-form__input" type="text" name="search" value="<?php if (isset($search)) {echo $search;}?>" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="/" class="tasks-switch__item <?php if ($date_filter === '') {echo 'tasks-switch__item--active';}?>">Все задачи</a>
            <a href="<?= '/?date_filter=today' ?>" class="tasks-switch__item <?php if ($date_filter === 'today') {echo 'tasks-switch__item--active';}?>">Повестка дня</a>
            <a href="<?= '/?date_filter=tomorrow' ?>" class="tasks-switch__item <?php if ($date_filter === 'tomorrow') {echo 'tasks-switch__item--active';}?>">Завтра</a>
            <a href="<?= '/?date_filter=overdue' ?>" class="tasks-switch__item <?php if ($date_filter === 'overdue') {echo 'tasks-switch__item--active';}?>">Просроченные</a>
        </nav>

        <label class="checkbox">
            <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
            <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php //if ($show_complete_tasks === 1) {echo 'checked';}?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">
        <?php         
        foreach ($tasks_of_users as $key => $value) { 
        if ($value['task_status'] && $show_complete_tasks === 0) {continue;}

        $task_class = 'tasks__item task'; // Класс для строки задачи

        if ($value['task_status']) {
            $task_class .= " task--completed";
        }

        if ($value['date_end']) {
            $time_to_deadline = floor((strtotime($value['date_end']) - strtotime('now'))/3600);
            if ($time_to_deadline <= 24) {
                $task_class .= " task--important";
            }
        }
        ?>
            <tr class="<?=$task_class ?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1" <?php if ($value['task_status'] === '1') {echo 'checked';}?>>
                        <span class="checkbox__text"><?=$value["task_name"] ?></span>
                        
                    </label>
                </td>

                
                <td class="task__file">
                <?php if ($value["file"]) { ?>
                <a class="download-link" href="<?=$value["file"] ?>"></a>
                <?php } ?>
                </td>
                

                <td class="task__date"><?=$value["date_end"] ?></td>
            </tr>
        <?php }
        if (empty($tasks_of_users)) {
            print('Ничего не найдено по вашему запросу');
        } ?>

    </table>
</main>