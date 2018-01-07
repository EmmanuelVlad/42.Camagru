<div class="column is-half is-offset-one-quarter">

    <p class="title is-size-3">Reset password</p>
    <p class="subtitle is-size-6">Enter your email address associated with your account to receive a password reset email. </p>

    <div id="notifications"></div>

    <form id="form" action="" method="post">
        <div class="field">
            <label for="email" class="label">Email</label>
            <p class="control has-icons-left has-icons-right">
                <input id="email" type="text" class="input" name="email" placeholder="Email">
                <span class="icon is-small is-left">
                    <i class="fa fa-envelope"></i>
                </span>
                <span class="icon is-small is-right"></span>
            </p>
        </div>

        <div class="field is-grouped is-grouped-centered">
            <p class="control">
                <button id="submit" type="submit" class="button is-primary">Reset</button>
            </p>
        </div>
    </form>

</div>