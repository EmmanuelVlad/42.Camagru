<?php

include "includes/config.php";
include "includes/mysql.php";
include "includes/header.php";
include "includes/menu.php";
?>

<form action="POST">
    <div class="input-group">
        <label for="nickname">Nickname :</label>
        <input type="text" name="nickname" id="nickname">
    </div>

</form>

<?php
include "includes/footer.php";
