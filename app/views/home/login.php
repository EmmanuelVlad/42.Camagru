<div class="column is-half is-offset-one-quarter">

    <p class="title is-size-3">Login</p>

    <?php   if (isset($data->error)) { ?>
            <div class="notification is-danger">
                <button class="delete" onclick="dismiss(this)"></button>
                <?=$data->error?>
            </div>
    <?php   } ?>

    <form action="" method="post">

        <div class="field">
            <label for="username" class="label">Username</label>
            <p class="control has-icons-left has-icons-right">
                <input id="username" type="text" class="input" name="username" placeholder="Username">
                <span class="icon is-small is-left">
                    <i class="fa fa-user"></i>
                </span>
                <span class="icon is-small is-right"></span>
            </p>
        </div>

        <div class="field">
            <label for="password" class="label">Password</label>
            <p class="control has-icons-left has-icons-right">
                <input id="password" type="password" class="input" name="password" placeholder="Password">
                <span class="icon is-small is-left">
                    <i class="fa fa-lock"></i>
                </span>
                <span class="icon is-small is-right"></span>
            </p>
        </div>

        <div class="field is-grouped is-grouped-centered">
            <p class="control">
                <input id="login" type="submit" class="button is-primary" value="Login">
            </p>
        </div>

        <div class="field is-grouped is-grouped-centered">
            <p class="control">
                <a href="<?=URL?>reset">Forgot your password?</a>
            </p>
        </div>
    </form>

</div>