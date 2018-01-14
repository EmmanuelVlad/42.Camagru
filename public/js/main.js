function base64ToBlob(base64, mime) 
{
    mime = mime || '';
    var sliceSize = 1024;
    var byteChars = window.atob(base64);
    var byteArrays = [];

    for (var offset = 0, len = byteChars.length; offset < len; offset += sliceSize) {
        var slice = byteChars.slice(offset, offset + sliceSize);

        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        var byteArray = new Uint8Array(byteNumbers);

        byteArrays.push(byteArray);
    }

    return new Blob(byteArrays, {type: mime});
}

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
    states = ['is-hovered', 'is-focused', 'is-loading'];
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

function addNotification(parent, color, message)
{
    var div = document.createElement("div");
    div.classList.add("notification");
    div.classList.add(color);

    var button = document.createElement("button");
    button.classList.add("delete");
    button.addEventListener('click', function() {
        dismiss(button);
    });

    var text = document.createTextNode(message);

    div.appendChild(button);
    div.appendChild(text);

    parent.insertBefore(div, parent.firstChild);
}

function deleteNotifications()
{
    var notifications = Array.from(document.getElementsByClassName('notification'));

    notifications.forEach(function(el) {
        el.remove();
    });
}

function dismiss(e) {
    e.parentElement.remove();
}

function reset(el) {
    el.value = "";
    changeColor(el, "");
    changeIcon(el.parentElement, "");
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

    var modals = Array.prototype.slice.call(document.querySelectorAll('.modal-close'), 0);
    if (modals.length > 0) {
        modals.forEach(function (el) {
        el.addEventListener('click', function () {
            if (el.parentElement.classList.contains('modal')) {
                el.parentElement.classList.remove('is-active');
            }
        });
      });
    }

});

