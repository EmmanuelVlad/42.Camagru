<div class="column is-half is-offset-one-quarter">

    <p class="title is-size-3">Reset password</p>
    <p class="subtitle is-size-6">Hey <?=htmlspecialchars($data->user->username)?>, you may now enter a new password!</p>

    <div id="notifications"></div>

    <form id="form" action="" method="post">
        <div class="field">
            <label for="password" class="label">Password</label>
            <p class="control has-icons-left has-icons-right">
                <input id="password" type="password" class="input" name="password" placeholder="Password" required>
                <span class="icon is-small is-left">
                    <i class="fa fa-lock"></i>
                </span>
                <span class="icon is-small is-right"></span>
            </p>
        </div>

        <div class="field">
            <label for="password2" class="label">Repeat password</label>
            <p class="control has-icons-left has-icons-right">
                <input id="password2" type="password" class="input" name="password2" placeholder="Repeat password" required>
                <span class="icon is-small is-left">
                    <i class="fa fa-lock"></i>
                </span>
                <span class="icon is-small is-right"></span>
            </p>
        </div>

        <div class="field is-grouped is-grouped-centered">
            <p class="control">
                <button id="submit" type="submit" class="button is-primary">Submit</button>
            </p>
        </div>
    </form>

</div>