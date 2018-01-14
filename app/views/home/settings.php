<div class="column is-half is-offset-one-quarter">

    <p class="title is-size-3">Settings</p>

    <div id="notifications"></div>

    <form id="form" action="" method="post">
        <div class="field">
            <label for="username" class="label">Username</label>
            <p class="control has-icons-left has-icons-right">
                <input id="username" type="text" class="input" name="username" value="<?=$data->user->username?>" placeholder="Username">
                <span class="icon is-small is-left">
                    <i class="fa fa-user"></i>
                </span>
                <span class="icon is-small is-right"></span>
            </p>
        </div>

        <div class="field">
            <label for="email" class="label">Email</label>
            <p class="control has-icons-left has-icons-right">
                <input id="email" type="email" class="input" name="email" value="<?=$data->user->email?>" placeholder="Email">
                <span class="icon is-small is-left">
                    <i class="fa fa-envelope"></i>
                </span>
                <span class="icon is-small is-right"></span>
            </p>
        </div>

        <div class="field">
            <label for="notification" class="label checkbox">Send email notification</label>
            <p class="control">
                <input id="notification" type="checkbox" name="notification" <?php echo ($data->user->notification) ? "checked" : "";  ?>>
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

        <div class="field">
            <label for="password2" class="label">Repeat password</label>
            <p class="control has-icons-left has-icons-right">
                <input id="password2" type="password" class="input" name="password2" placeholder="Repeat password">
                <span class="icon is-small is-left">
                    <i class="fa fa-lock"></i>
                </span>
                <span class="icon is-small is-right"></span>
            </p>
        </div>

        <div class="field is-grouped is-grouped-centered">
            <p class="control">
                <button id="submit" type="submit" class="button is-primary">Update</button>
            </p>
        </div>
    </form>

</div>