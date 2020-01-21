<?php // показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
?>

<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach($projects as $key => $value) { ?>
                <li class="main-navigation__list-item">
                    <a class="main-navigation__list-item-link" href="#"><?=$value['project_name']?></a>
                    <span class="main-navigation__list-item-count"><?=count_tasks($value['id'], $tasks_on_project) ?></span>
                </li>
            <?php } ?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button"
        href="pages/form-project.html" target="project_add">Добавить проект</a>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="post" autocomplete="off">
        <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
            <a href="/" class="tasks-switch__item">Повестка дня</a>
            <a href="/" class="tasks-switch__item">Завтра</a>
            <a href="/" class="tasks-switch__item">Просроченные</a>
        </nav>

        <label class="checkbox">
            <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
            <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if ($show_complete_tasks === 1) {echo 'checked';}?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">
        <?php foreach ($tasks as $key => $value) { 
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
                        <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1">
                        <span class="checkbox__text"><?=$value["task_name"] ?></span>
                    </label>
                </td>

                <td class="task__date"><?=$value["date_end"] ?></td>
            </tr>
        <?php } ?>

    </table>
</main>