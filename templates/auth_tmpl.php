<section class="content__side">
    <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>

    <a class="button button--transparent content__side-button" href="form-authorization.html">Войти</a>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Вход на сайт</h2>

    <form class="form" action="auth.php" method="post" autocomplete="off">
    <div class="form__row">
        <label class="form__label" for="email">E-mail <sup>*</sup></label>
        <?php $classname = isset($errors['email']) ? "form__input--error" : ""; ?>
        <input class="form__input <?= $classname ?>" type="text" name="email" id="email" value="<?= get_post_val('email') ?>" placeholder="Введите e-mail">

        <?php if (isset($errors['email'])) { ?>
        <p class="form__message"><?=$errors['email'] ?></p>
        <?php } ?> 
    </div>

    <div class="form__row">
        <label class="form__label" for="password">Пароль <sup>*</sup></label>
        <?php $classname = isset($errors['password']) ? "form__input--error" : ""; ?>
        <input class="form__input <?= $classname ?>" type="password" name="password" id="password" value="<?= get_post_val('password') ?>" placeholder="Введите пароль">
        
        <?php if (isset($errors['password'])) { ?>
        <p class="form__message"><?=$errors['password'] ?></p>
        <?php } ?>
    </div>

    <div class="form__row form__row--controls">
        <?php if (!empty($errors)) { ?>
            <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
        <?php } ?>

        <?php if ($login_error) { ?>
            <p class="error-message">Вы ввели неверный email/пароль</p>
        <?php } ?>
        <input class="button" type="submit" name="" value="Войти">
    </div>
    </form>

</main>