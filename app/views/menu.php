
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
<?php if (Session::get('user')) { ?>
        <div class="navbar-end">
          <a href="/" class="navbar-item"><?=Session::get('user')?></a>
        </div>
<?php  } else { ?>
        <div class="navbar-end">
          <a href="/login" class="navbar-item">Log in</a>
          <a href="/register" class="navbar-item">Register</a>
        </div>
<?php  } ?>
      </div>
    </div>
  </nav>
</header>



