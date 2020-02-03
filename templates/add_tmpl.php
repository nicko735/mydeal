    <?php require_once 'block/sidebar_menu.php'; ?>

      <main class="content__main">
        <h2 class="content__main-heading">Добавление задачи</h2>

        <form class="form"  action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
          <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>
            <?php $classname = isset($errors['name']) ? "form__input--error" : ""; ?>
            <input class="form__input <?= $classname ?>" type="text" name="name" id="name" value="<?= get_post_val('name') ?>" placeholder="Введите название">
            <?php if (isset($errors['name'])) { ?>
              <p class="form__message"><?=$errors['name'] ?></p>
            <?php } ?>            
          </div>

          <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>
            <?php $classname = isset($errors['project']) ? "form__input--error" : ""; ?>
            <select class="form__input form__input--select <?= $classname ?>" name="project" id="project">
              <?php foreach($projects_of_users as $key => $value) { ?>
                <?php $selected = $value["id"] == get_post_val('project') ? "selected" : "";?>
                <option value="<?=$value["id"]?>" <?= $selected ?>><?=$value["project_name"]?></option>
              <?php } ?>
            </select>
            <?php if (isset($errors['project'])) { ?>
              <p class="form__message"><?=$errors['project'] ?></p>
            <?php } ?>  
          </div>

          <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>
            <?php $classname = isset($errors['date']) ? "form__input--error" : ""; ?>
            <input class="form__input form__input--date <?= $classname ?>" type="text" name="date" id="date" value="<?= get_post_val('date') ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
            <?php if (isset($errors['date'])) { ?>
              <p class="form__message"><?=$errors['date'] ?></p>
            <?php } ?>  
          </div>

          <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
              <input class="visually-hidden" type="file" name="file" id="file" value="">

              <label class="button button--transparent" for="file">
                <span>Выберите файл</span>
              </label>
            </div>
          </div>

          <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
          </div>
        </form>
      </main>