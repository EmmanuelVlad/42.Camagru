function changeColor(element, color) {
    colors = ['is-primary', 'is-info', 'is-success', 'is-warning', 'is-danger'];
    colors.forEach(function(color) {
        if (element.classList.contains(color)) {
            element.classList.remove(color);
        }
    });
    if (color) {
        element.classList.add(color);
    }
}

function changeState(element, state) {
    states = ['is-hovered', 'is-focused'];
    states.forEach(function(state) {
        if (element.classList.contains(state)) {
            element.classList.remove(state);
        }
    });
    if (state) {
        element.classList.add(state);
    }
}

function changeIcon(parent, icon, position = "right")
{
    var element = parent.getElementsByClassName(`is-${position}`)[0];
    (icon) ? element.innerHTML = `<i class="fa fa-${icon}"></i>` : element.innerHTML = "";
}

document.addEventListener("DOMContentLoaded", function(event) { 

    var $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
    if ($navbarBurgers.length > 0) {
      $navbarBurgers.forEach(function ($el) {
        $el.addEventListener('click', function () {
          var target = $el.dataset.target;
          var $target = document.getElementById(target);
          $el.classList.toggle('is-active');
          $target.classList.toggle('is-active');
        });
      });
    }

});

