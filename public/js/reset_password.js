document.addEventListener("DOMContentLoaded", function(event) {
    var form = document.getElementById('form');
    var notifications = document.getElementById('notifications');
    var password = document.getElementById('password');
    var password2 = document.getElementById('password2');
    var submit = document.getElementById('submit');
    var loading = false;
    var key = window.location.pathname.split('/');
    for (let i = key.length; i > 0; i--) {
        if (key[i]) {
            key = key[i];
            break;
        }
    }

    // On password change
    password.addEventListener("input", function(event) {
        if (/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/.test(password.value)) {
            changeColor(password, "is-success");
            changeIcon(password.parentElement, "check");
        } else if (password.value === "") {
            reset(password);
        } else {
            changeColor(password, "is-danger");
            changeIcon(password.parentElement, "times");
        }

        if (/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/.test(password.value) && password2.value === password.value) {
            changeColor(password2, "is-success");
            changeIcon(password2.parentElement, "check");
        } else if (password2.value === "") {
            reset(password2);
        } else {
            changeColor(password2, "is-danger");
            changeIcon(password2.parentElement, "times");
        }
    });

    // On password2 change
    password2.addEventListener("input", function(event) {
        if (/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/.test(password.value) && password2.value === password.value) {
            changeColor(password2, "is-success");
            changeIcon(password2.parentElement, "check");
        } else if (password2.value === "") {
            reset(password2);
        } else {
            changeColor(password2, "is-danger");
            changeIcon(password2.parentElement, "times");
        }
    });

    // On reset click
    form.addEventListener("submit", function(event) {
        if (!loading) {
            deleteNotifications();
            loading = true;

            changeState(submit, 'is-loading');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', `${url}api/reset_password`);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        addNotification(notifications, 'is-success', "You may now log in with your new password!")
                        reset(password);
                        reset(password2);
                    } else {
                        if (data.errors) {
                            data.errors.forEach(function(el) {
                                addNotification(notifications, 'is-danger', el);
                            });
                        }
                    }
                }
                changeState(submit, '');
                loading = false;
            };
            xhr.send(encodeURI(`key=${key}&password=${password.value}&password2=${password2.value}`));
        }
        event.preventDefault();
    });
});

