
<header>
  <nav class="navbar is-dark" role="navigation" aria-label="main navigation">
    <div class="container">
      <div class="navbar-brand">
        <a href="<?=URL?>" class="navbar-item">Camagru</a>
        <div class="navbar-burger burger" data-target="navMenu">
        <span></span>
        <span></span>
        <span></span>
        </div>
      </div>
      <div id="navMenu" class="navbar-menu">
        <div class="navbar-start">
          <a href="<?=URL?>galery" class="navbar-item">Galery</a>
        </div>
<?php if (Session::get('user')) { ?>
        <div class="navbar-end">
          <a href="<?=URL?>settings" class="navbar-item">Settings</a>
          <a href="<?=URL?>logout" class="navbar-item">Log out</a>
        </div>
<?php  } else { ?>
        <div class="navbar-end">
          <a href="<?=URL?>login" class="navbar-item">Log in</a>
          <a href="<?=URL?>register" class="navbar-item">Register</a>
        </div>
<?php  } ?>
      </div>
    </div>
  </nav>
</header>



