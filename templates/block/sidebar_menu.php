<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach($projects_of_users as $key => $value) { ?>
                <li class="main-navigation__list-item <?php
                    if ($project_id === $value['id'] ) {
                        print('main-navigation__list-item--active');
                    }
                ?>">
                    <a class="main-navigation__list-item-link" href="<?='/?project_id=' . $value['id'] ?>"><?=$value['project_name']?></a>
                    <span class="main-navigation__list-item-count"><?=count_tasks($value['id'], $tasks_on_project) ?></span>
                </li>
            <?php } ?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button"
        href="pages/form-project.html" target="project_add">Добавить проект</a>
</section>